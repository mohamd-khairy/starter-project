<?php

namespace Modules\Report\Http\Controllers;

use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Modules\Report\Entities\ArchiveFile;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ArchiveFilesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:read-archive_file|read-report_builder', ['only' => ['index', 'download']]);
        $this->middleware('permission:delete-archive_file', ['only' => ['destroy']]);
    }

    /**
     * @param $modalType
     * @return Application|Factory|View
     */
    public function index($modalType)
    {
        try {
            Artisan::call('files:export');
        } catch (Exception $e) {
            //continue
        }

        $files = ArchiveFile::where('model_type', $modalType)->when(request()->has('site_id'), function ($query) {
            $query->where('site_id', request()->site_id);
        })->with('site')->latest()->get();

        return view('report::archive.index', [
            'title' => __('dashboard.archive_files'),
            'files' => $files,
            'model_type' => $modalType,
        ]);
    }

    /**
     * @param $file
     * @return RedirectResponse
     */
    public function download($file): RedirectResponse
    {
        $file = ArchiveFile::findOrFail((int)$file);

        if (Storage::exists($file->url)) {
            return redirect()->back()->with([
                'message' => __('dashboard.files_prepared_successfully'),
                'file' => Storage::url($file->url)
            ]);
        }

        return redirect()->back()->with([
            'message' => __('dashboard.file_not_found'),
            'file' => null
        ]);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        ArchiveFile::where('id', $id)->delete();

        return response()->json([
            'message' => trans('dashboard.file_delete_successfully')
        ]);

    }

    /**
     * @param $file_id
     * @return JsonResponse|BinaryFileResponse
     */
    public function downloadFile($file_id)
    {
        $file = ArchiveFile::find($file_id);
        if (Storage::exists($file->url)) {

            $file_path = Storage::url($file->url);
            $headers = ['Content-Type' => 'application/pdf'];

            return response()->download($file_path, $file->name, $headers);

        }

        return response()->json(['message' => __('dashboard.file_will_prepare_soon')], 400);
    }
}
