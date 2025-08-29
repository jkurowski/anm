<?php

namespace App\Http\Controllers\Front\Cron;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

//CMS
use App\Services\Vox\RealEstateParser;
use App\Models\Investment;

class IndexController extends Controller
{
    protected $parser;

    public function __construct(RealEstateParser $parser)
    {
        $this->parser = $parser;
    }

    public function updateVOX($id = 1)
    {
        $id = 1;

        Log::channel('cron')->info("Starting updateVOX for investment ID: {$id}");

        $investment = Investment::find($id);
        if (!$investment) {
            Log::channel('cron')->error("Investment ID {$id} not found.");
            return;
        }

        $vox_url = $investment->vox_url;
        if (!$vox_url) {
            Log::channel('cron')->error("Investment ID {$id} has no VOX URL.");
            return;
        }

        try {
            Log::channel('cron')->info("Fetching XML content from: {$vox_url}");
            $xmlContent = file_get_contents($vox_url);

            if (!$xmlContent) {
                throw new \Exception('Failed to retrieve XML content from the URL');
            }

            Log::channel('cron')->info("Parsing XML content for Investment ID: {$id}");
            $parsedData = $this->parser->parse($xmlContent);
        } catch (\Exception $e) {
            Log::channel('cron')->error("Error fetching/parsing XML for Investment ID {$id}: " . $e->getMessage());
            return;
        }

        $imported = $this->parser->importProperties($parsedData, $investment);
        Log::channel('cron')->info("Successfully imported {$imported} properties for Investment: {$investment->name}");
    }
}
