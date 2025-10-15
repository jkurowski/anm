<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

//CMS
use App\Models\Plan;

class InvestmentService
{
    public function uploadThumb(string $title, UploadedFile $file, object $model, bool $delete = false)
    {

        if ($delete) {
            if (File::isFile(public_path('investment/thumbs/' . $model->file_thumb))) {
                File::delete(public_path('investment/thumbs/' . $model->file_thumb));
            }
        }

        $name = date('His').'_'.Str::slug($title).'.' . $file->getClientOriginalExtension();
        $name_webp = date('His') . '_' . Str::slug($title) . '.webp';

        $file->storeAs('thumbs', $name, 'investment_uploads');

        $filepath = public_path('investment/thumbs/' . $name);
        Image::make($filepath)
            ->fit(
                config('images.investment.thumb_width'),
                config('images.investment.thumb_height')
            )
            ->save($filepath);

        $file_path_webp = public_path('investment/thumbs/webp/' . $name_webp);
        Image::make($filepath)->encode('webp')->save($file_path_webp);

        $model->update(['file_thumb' => $name, 'file_webp' => $name_webp]);
    }

    public function uploadLogo(string $title, UploadedFile $file, object $model, bool $delete = false)
    {

        if ($delete) {
            if (File::isFile(public_path('investment/logo/' . $model->file_logo))) {
                File::delete(public_path('investment/logo/' . $model->file_logo));
            }
        }

        $name = date('His').'_logo-'.Str::slug($title).'.' . $file->getClientOriginalExtension();
        $file->storeAs('logo', $name, 'investment_uploads');

        $filepath = public_path('investment/logo/' . $name);
        Image::make($filepath)
            ->fit(
                config('images.investment.logo_width'),
                config('images.investment.logo_height')
            )
            ->save($filepath);

        $model->update(['file_logo' => $name]);
    }

    public function uploadHeader(string $title, UploadedFile $file, object $model, bool $delete = false)
    {
        if ($delete) {
            if (File::isFile(public_path('investment/header/' . $model->file_header))) {
                File::delete(public_path('investment/header/' . $model->file_header));
            }
        }

        $name = date('His').'_header-'.Str::slug($title).'.' . $file->getClientOriginalExtension();
        $file->storeAs('header', $name, 'investment_uploads');

        $filepath = public_path('investment/header/' . $name);
        Image::make($filepath)
            ->fit(
                config('images.investment.header_width'),
                config('images.investment.header_height')
            )
            ->save($filepath);

        $model->update(['file_header' => $name]);
    }

    public function uploadPlan(object $model, UploadedFile $file)
    {

        if ($model->plan()->exists()) {
            if (File::isFile(public_path('investment/plan/' . $model->plan()->first()->file))) {
                File::delete(public_path('investment/plan/' . $model->plan()->first()->file));
            }
        }

        $name = date('His') . '_' . Str::slug($model->name) . '.' . $file->getClientOriginalExtension();
        $name_webp = date('His') . '_' . Str::slug($model->name) . '.webp';

        $file->storeAs('plan', $name, 'investment_uploads');

        $filepath = public_path('investment/plan/' . $name);
        Image::make($filepath)->resize(
            config('images.plan.width'),
            config('images.plan.height'),
            function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save($filepath);

        $file_path_webp = public_path('investment/plan/webp/' . $name_webp);
        Image::make($filepath)->encode('webp')->save($file_path_webp);

        Plan::updateOrCreate(
            ['investment_id' => $model->id],
            ['file' => $name],
            ['file_webp' => $name_webp]
        );
    }

    public function uploadBrochure(string $title, UploadedFile $file, object $model, bool $delete = false)
    {
        if ($delete && !empty($model->file_brochure)) {
            $brochurePath = public_path('investment/brochure/' . $model->file_brochure);

            if (File::exists($brochurePath) && File::isFile($brochurePath)) {
                File::delete($brochurePath);
            }
        }

        $name = date('His') . '_' . Str::slug($title) . '.' . $file->getClientOriginalExtension();

        // Save file to public/investment/brochure
        $file->move(public_path('investment/brochure'), $name);

        // Update model
        $model->update(['file_brochure' => $name]);
    }
}
