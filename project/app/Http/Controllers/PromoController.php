<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;


class PromoController extends Controller
{
    public function index() {
        return Inertia::render('Promo/index');
    }

    private function generateRandomString($characters, $length, $block_divider_count, $first_block_count) {
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            if ($i == $first_block_count) {
                $randomString .= ' ';
            }
            if ($i > $first_block_count && ($i - $first_block_count) % $block_divider_count === 0) {
                $randomString .= ' ';
            }
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomString;
    }

    public function getPromo(Request $request)
    {
        $start_time = microtime(true);
        $seconds_to_fail = 28;

        $request->validate([
            'prefix' => ['nullable', 'string'],
            'postfix' => ['nullable', 'string'],
            'usedSymbols' => ['required', 'string'],
            'blocks_count' => ['required', 'numeric', 'min:0', 'max:5'],
            'first_block' => ['required', 'numeric', 'min:1', 'max:10'],
            'elements_in_block' => ['required', 'numeric', 'min:0', 'max:5'],
            'codes_to_count' => ['required', 'numeric', 'min:10', 'max:1000000'],
        ]);

        $codes = [];
        $first_block_count = $request->first_block;
        $totalCodes = $request->codes_to_count;
        $length = ($request->blocks_count * $request->elements_in_block) + $request->first_block;
        $usedSymbols = $request->usedSymbols;
        $prefix = $request->prefix ?? '';
        $postfix = $request->postfix ?? '';
        $block_divider_count = $request->elements_in_block;


        while (count($codes) < $totalCodes) {
            $promoCode = $prefix . ' ' . $this->generateRandomString($usedSymbols, $length, $block_divider_count, $first_block_count) . ' ' . $postfix;

            // Проверка на уникальность промокода
            while (isset($codes[$promoCode])) {
                $filePath = 'promoCodes.txt';
                file_put_contents($filePath, '
                Внимание!
                Сгенерировать уникальные коды по вашим показателям не удалось.
                Добавьте символов для генерации и/или измените количество кодов!');
                return response()->file($filePath)->deleteFileAfterSend(true);
            }

            $codes[$promoCode] = true;

            if (microtime(true) - $start_time >= $seconds_to_fail) {
                $filePath = 'promoCodes.txt';
                file_put_contents($filePath, 'Превышено время генерации промокодов. Пожалуйста, измените условия генерации');
                return response()->file($filePath)->deleteFileAfterSend(true);
            }
        }

        $filePath = 'promoCodes.txt';
        file_put_contents($filePath, implode("\r\n", array_keys($codes)));
        return response()->file($filePath)->deleteFileAfterSend(true);
    }

//    public function getPromoToDatabase(Request $request)
//    {
//        // to table
//        Promocode::truncate();
//        $validatedData = $request->validate([
//            'prefix' => ['nullable', 'string'],
//            'postfix' => ['nullable', 'string'],
//            'usedSymbols' => ['required', 'string'],
//            'blocks_count' => ['required', 'numeric', 'min:0', 'max:5'],
//            'first_block' => ['required', 'numeric', 'min:1', 'max:10'],
//            'elements_in_block' => ['required', 'numeric', 'min:0', 'max:5'],
//            'codes_to_count' => ['required', 'numeric', 'min:10', 'max:10000000'],
//        ]);
//
//        // Параметры для задачи
//        $params = [
//            'prefix' => $validatedData['prefix'] ?? '',
//            'postfix' => $validatedData['postfix'] ?? '',
//            'usedSymbols' => $validatedData['usedSymbols'],
//            'blocks_count' => $validatedData['blocks_count'],
//            'first_block' => $validatedData['first_block'],
//            'elements_in_block' => $validatedData['elements_in_block'],
//            'codes_to_count' => $validatedData['codes_to_count'],
//        ];
//
//        // Диспетчеризация задачи с указанием количества вызовов php artisan queue:work --timeout=999
//
//        $counts = $validatedData['codes_to_count'] / 20;
//        GeneratePromocodes::dispatch($params, $counts)->onQueue('1');
//        GeneratePromocodes::dispatch($params, $counts)->onQueue('2');
//       GeneratePromocodes::dispatch($params, $counts)->onQueue('3');
//       GeneratePromocodes::dispatch($params, $counts)->onQueue('4');
//        GeneratePromocodes::dispatch($params, $counts)->onQueue('5');
//
//        GeneratePromocodes::dispatch($params, $counts)->onQueue('6');
//        GeneratePromocodes::dispatch($params, $counts)->onQueue('7');
//        GeneratePromocodes::dispatch($params, $counts)->onQueue('8');
//        GeneratePromocodes::dispatch($params, $counts)->onQueue('9');
//        GeneratePromocodes::dispatch($params, $counts)->onQueue('10');

//        return response()->json(['success' => true, 'message' => 'Задача на генерацию промокодов поставлена в очередь']);
//    }

//    public function writeToFile()
//    {
//
//        $promocodes = Promocode::orderBy('id', 'asc')->take(210000)->pluck('promo')->toArray();
//
//        // Путь к файлу, в который будут сохранены промокоды
//        $filePath = 'promoCodes.txt';
//
//        // Сохранение промокодов в файл
//        file_put_contents($filePath, implode("\r\n", $promocodes));
//
//        // Удаление записей из таблицы
////        Promocode::whereIn('promo', $promocodes)->delete();
//
//        // Возвращение файла для скачивания и автоматическое удаление файла после отправки
//        return response()->file($filePath)->deleteFileAfterSend(true);
//
//    }
}
