<?php

namespace Botble\Franchise\Http\Controllers\Fronts;

use Botble\DataSynchronize\Exporter\Exporter;
use Botble\DataSynchronize\Http\Controllers\ExportController;
use Botble\Franchise\Exporters\ProductExporter;

class ExportProductController extends ExportController
{
    protected function getExporter(): Exporter
    {
        return ProductExporter::make();
    }
}
