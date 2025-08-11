<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\EncostInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class EncostRepository implements EncostInterface
{

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function getEndpoints(): Response
    {
        return Http::withToken(config('custom.ENCOST_TOKEN'))
            ->get(config('custom.ENCOST_API') . 'endpoints')->throw();
    }


    public function getEncostHierarchyMachinesList()
    {
        return Http::withToken(config('custom.ENCOST_TOKEN'))
            ->get(config('custom.ENCOST_API') . 'settings/nodes?include_endpoints=true')->throw();
    }

    public function getEncostHierarchyIdles($hierarchy_id)
    {
        return Http::withToken(config('custom.ENCOST_TOKEN'))
            ->get(config('custom.ENCOST_API') . 'settings/reasons?hierarchy_node_id=' . $hierarchy_id . '&is_active=true')
            ->throw();
    }


    public function getIdlesData(int $encost_machine_id, string $date_start, string $date_finish)
    {
        return Http::withToken(config('custom.ENCOST_TOKEN'))
            ->get(config('custom.ENCOST_API') . 'extract_data/' .
                $encost_machine_id . '?datetime_from=' .
                $date_start . '&datetime_to=' . $date_finish .
                '&additional_columns=Длительность, ч;Комментарий'
            )->throw();


    }
}
