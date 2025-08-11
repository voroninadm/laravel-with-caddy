<?php

namespace App\Http\Controllers\ThirdPartyApi;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\ApiSpbInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SPbApiController extends Controller
{

    public function __construct(protected ApiSpbInterface $apiSpbInterface)
    {
    }

    /** Обращение к db в СПБ для получения данных по карте
     * @param Request $request
     * @return JsonResponse
     */
    public function getOrders(Request $request): JsonResponse
    {
        $response = $this->apiSpbInterface->getOrders($request->tkn);

        if ($response->successful()) {
            $data = $response->json();
            $info = $data['orders'][0];
            if (!empty($info)) {

                return response()->json([
                    'json_ok' => true,
                    'create_date' => $info['CREATE_DATE'], // дата создания
                    'finish_date' => $info['FINISH_DATE'], // дата создания
                    'customer' => $info['CUSTOMER_NAME'], // заказчик
                    'print_title' => $info['ORDER_NAME'], // название заказа
                    'colors' => $info['PAINTS_COUNT'], // количeство красок
                    'circulation_sht' => $info['ISSUE_SHT'], // план в штуках
                    'issue' => $info['ISSUE_KG'], // план кг
                    'circulation_kg' => $info['PLAN_PLEN_WELD_KG'], // план пленки
                    'circulation_length' => $info['PLAN_PLEN_WELD_POGM'], // план пленки пог.м.
                    'design_number' => $info['DESIGN_NUMBER'], // номер дизайна
                    'sleeves_diameter' => $info['SLEEVES'], // номер дизайна

                    // сварка
                    'length' => $info['BAG_LENGTH'], // длина пакета
                    'product_width' => $info['BAG_WIDTH_NEW'], // ширина пакета
                    'bottom' => $info['BOTTOM'], // дно

                    //резка
                    'thickness' => $info['THICKNESS'], // толщина
                    'streams' => $info['STREAMS'], // кол-во ручьев
                    'streams_widths' => explode('|', $info['STREAMS_WIDTHS']), //ширина ручьев

                    //экструзия
                    'hose_width' => $info['HOSE_WIDTH'],
                    'extrusion_streams' => $info['EXTR_STRIM'],
                    'activation_width' => $info['ACTIVATION_WIDTH'], // ширина полотна

                    //плановое время по работам
                    'extrusion_time' => $info['ETIME'],
                    'printing_time' => $info['PTIME'],
                    'lamination_time' => $info['LTIME'],
                    'cutting_time' => $info['CTIME'],
                    'welding_time' => $info['WTIME'],
                ]);
            }
        }

        return response()->json([
            'json_ok' => false,
        ]);
    }
}
