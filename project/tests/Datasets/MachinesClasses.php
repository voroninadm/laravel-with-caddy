<?php

use App\Models\Elog\Cutting;
use App\Models\Elog\Extrusion;
use App\Models\Elog\Lamination;
use App\Models\Elog\Printing;
use App\Models\Elog\Recycling;
use App\Models\Elog\Welding;

dataset('MachinesClasses', [
   Printing::class,
    Lamination::class,
    Welding::class,
    Cutting::class,
    Extrusion::class
    ]);
    // no recycling because of no "tkn" field;
