<?php

namespace App\Services\Vox;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;

// CMS
use App\Models\Investment;
use App\Models\Property;
use App\Models\PriceHistory;

class RealEstateParser
{
    public function parse(string $xmlString): array
    {
        $xml = new SimpleXMLElement($xmlString);

        $parsedRealEstates = [];
        foreach ($xml->realestate as $realEstate) {
            $parsedRealEstates[] = $this->mapRealEstate($realEstate);
        }

        return $parsedRealEstates;
    }

    public function importProperties(array $parsedData, Investment $investment)
    {
        $importedCount = 0;

        foreach ($parsedData as $propertyData) {
            //if ($propertyData['type']['id'] == 1) {
            $preparedData = [
//                    'investment_id' => $investment->id,
//                    'investment_stage' => $propertyData['investment_stage'],
//                    'vox_id' => $propertyData['id'],
//                    'name' => 'Mieszkanie ' . ($propertyData['name'] ?? 'N/A'),
//                    'number' => $propertyData['name'] ?? null,
//                    'building_name' => $propertyData['building'] ?? null,
                'status' => $this->getStatusById($propertyData['status']['id']) ?? null,
                //'active' => (int)$propertyData['active'] ?? null,
                'balcony_area' => $propertyData['balcony_area'] ?? null,
                'terrace_area' => $propertyData['terrace_area'] ?? null,
                'area' => $propertyData['area'] ?? null,
                //'area_search' => round($propertyData['area']) ?? null,
                'rooms' => $propertyData['rooms'] ?? null,
                //'floor_id' => $propertyData['floor'] ?? null,
                'price' => $propertyData['price']['net'] ?? null,
                'price_brutto' => $propertyData['price']['gross'] ?? null,
                'type_vox' => $propertyData['type']['id'] ?? 0,
                'promotion_price' => !empty($propertyData['promotion']['price'])
                    ? $propertyData['promotion']['price']
                    : null,
                //'ask_for_price' => $propertyData['ask_for_price'] ?? 0,
                //'file' => $propertyData['links']['plan'] ?? null,
                //'file_pdf' => $propertyData['links']['card'] ?? null,
            ];

            try {
                $property = Property::where('vox_id', $propertyData['id'])->first();

                if ($property) {
                    $property->update($preparedData);

                    if (!empty($propertyData['price_change_history'])) {
                        $response = Http::get($propertyData['price_change_history']);

                        if ($response->ok()) {
                            $xml = simplexml_load_string($response->body(), "SimpleXMLElement", LIBXML_NOCDATA);

                            // Delete existing history for this property
                            PriceHistory::where('real_estate_id', $property->id)->delete();

                            foreach ($xml->realestate->price_history->price_change as $change) {
                                PriceHistory::create([
                                    'real_estate_id' => $property->id,
                                    'date_modified' => (string) $change->date_modified,
                                    'price_gross' => (float) $change->after->price,
                                    'price_net' => (float) $change->after->price_net,
                                    'price_per_mkw' => (float) $change->after->pricemkw,
                                    'price_per_mkw_net' => (float) $change->after->pricemkw_net,
                                    'price_before_gross' => (float) $change->before->price,
                                    'price_before_net' => (float) $change->before->price_net,
                                    'price_before_per_mkw' => (float) $change->before->pricemkw,
                                    'price_before_per_mkw_net' => (float) $change->before->pricemkw_net,
                                ]);
                            }
                        } else {
                            // Handle HTTP error
                            logger()->error('Failed to fetch price history', [
                                'url' => $propertyData['price_change_history'],
                                'status' => $response->status(),
                            ]);
                        }
                    }
                }

                // Log success to custom channel
                Log::channel('property_import')->info('Property imported successfully', [
                    'vox_id' => $propertyData['id'],
                    'data' => $preparedData,
                ]);


                $importedCount++;
            } catch (\Exception $e) {
                Log::error('Failed to import property', [
                    'vox_id' => $propertyData['id'],
                    'error' => $e->getMessage(),
                    'data' => $preparedData,
                ]);
            }
            //}
        }

        //die();
        return $importedCount;
    }

    function getStatusById(int $id): ?int
    {
        // mapa ID -> status
        $map = [
            1 => 1,                                 // Na sprzedaż
            2 => 4,                                 // Rezerwacja ustna
            3 => 2,                                 // Rezerwacja
            4 => 3, 5 => 3, 6 => 3, 7 => 3, 8 => 3, 9 => 3  // Sprzedane
        ];

        return $map[$id] ?? 1;
    }

    private function mapRealEstate(SimpleXMLElement $realEstate): array
    {
        return [
            'id' => (int) $realEstate->id,
            'investment_id' => (int) $realEstate->investment_id,
            'investment_name' => (string) $realEstate->investment_name,
            'investment_stage' => (int) $realEstate->investment_stage,
            'building' => (string) $realEstate->building,
            'name' => (string) $realEstate->name,
            'local_number' => (int) $realEstate->local_number,
            'status' => [
                'id' => (int) $realEstate->status_id,
                'name' => (string) $realEstate->status_name,
            ],
            'area' => (float) $realEstate->area,
            'rooms' => (int) $realEstate->rooms,
            'floor' => (int) $realEstate->floor,
            'balcony_area' => (int) $realEstate->balkon,
            'terrace_area' => (int) $realEstate->taras,
            'ask_for_price' => (int) $realEstate->ask_for_price,
            'completion_date' => (string) $realEstate->completion_date,
            'price' => [
                'gross' => (float) $realEstate->price,
                'net' => (float) $realEstate->price_net,
                'per_mkw' => (float) $realEstate->pricemkw,
                'per_mkw_net' => (float) $realEstate->pricemkw_net,
            ],
            'city' => (string) $realEstate->city,
            'type' => [
                'id' => (int) $realEstate->type_id,
                'name' => (string) $realEstate->type,
            ],
            'staircase' => (string) $realEstate->staircase,
            'description' => (string) $realEstate->description->asXML(),
            'direction' => (string) $realEstate->direction,
            'active' => (string) $realEstate->dont_send_to_www,
            'promotion' => [
                'is_promoted' => $realEstate->promotion,
                'price' => (string) $realEstate->promotion_price,
                'date_from' => (string) $realEstate->promotion_date_from,
                'date_to' => (string) $realEstate->promotion_date_to,
            ],
            'links' => [
                'plan' => (string) $realEstate->plan_link,
                'card' => (string) $realEstate->card_link,
                'virtual_walk' => (string) $realEstate->virtual_walk,
                'view360' => (string) $realEstate->view360,
            ],
            'date_modified' => (string) $realEstate->date_modified,
            'price_change_history' => (string) $realEstate->price_change_history,
        ];
    }
}
