<?php

namespace App\Http\Interfaces;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

interface EncostInterface
{
    /** Получение списка машин с id
     * @return mixed
     */
    public function  getEndpoints();


    /** Получение списка типов машин (иерархии) со списком относящихся к ним машин
     * @return mixed
     */
    public function  getEncostHierarchyMachinesList();


    /** Получение списка простоев по id-типу машины (id иерархии)
     * @param $hierarchy_id
     * @return mixed
     */
    public function  getEncostHierarchyIdles($hierarchy_id);

    /** Получение данных по id-машины и датам работ
     * @param int $encost_machine_id
     * @param string $date_start
     * @param string $date_finish
     * @return mixed
     */
    public function  getIdlesData(int $encost_machine_id, string $date_start, string $date_finish);
}
