<?php

namespace Botble\Franchise\Http\Controllers\Fronts;

use Botble\DataSynchronize\Http\Controllers\ImportController;
use Botble\DataSynchronize\Importer\Importer;
use Botble\Franchise\Importers\ProductImporter;
use Illuminate\Http\Request;

class ImportProductController extends ImportController
{
    protected function getImporter(): Importer
    {
        return ProductImporter::make();
    }

    protected function prepareImporter(Request $request): Importer
    {
        /**
         * @var ProductImporter $importer
         */
        $importer = parent::prepareImporter($request);

        return $importer->setImportType($request->input('type'));
    }
}
