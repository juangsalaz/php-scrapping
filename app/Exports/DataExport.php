<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use App\UrlPro;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Facades\Excel;
use KubAT\PhpSimple\HtmlDomParser;

class DataExport implements FromView
{

    public function view(): View
    {
        $data = Excel::toArray(new UrlPro, 'internet-urls.xlsx');
        $urls = [];

        $result = [];

        for ($i=0; $i < count($data[0]); $i++) { 
            $result['result'][] = $this->process($data[0][$i][3]);
        }

        return view('excel_report', $result);
    }

    public function process($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);

        $dom = HtmlDomParser::str_get_html($response);

       if (empty($dom->find('h1.not-found-title'))) {
            $page_title = $dom->find('h1.entry-title')[0]->innertext;
            preg_match('#\((.*?)\)#', $page_title, $match);
            $city_state = $match[1];

            $page_title_array = explode(" ", $page_title);
            $zip_code = $page_title_array[3];

            $array_of_precentage = [];
            foreach ($dom->find('section.internet-data > div.et_column_last > ul > li > p') as $test) {
                $string_array = explode(" ", $test->innertext);
                $percentage = $string_array[0];
                $array_of_precentage[] = $percentage;
            }

            $array_of_providers = [];
            foreach ($dom->find('section#ISPSummary')[0]->children(1)->children(0)->children() as $provider) {
                if (isset($provider->find('td', 0)->innertext)) {
                    $array_of_providers[] = $provider->find('td', 0)->innertext;
                }
            }

            $providers = "";
            foreach ($array_of_providers as $provider) {
                if ($providers != "") {
                    $providers = $providers.', '.$provider;
                } else {
                    $providers = $provider;
                }
            }

            $result_array = array(
                "zip" => $zip_code,
                "city" => $city_state,
                "fiber" => $array_of_precentage[0],
                "cable" => $array_of_precentage[1],
                "dsl" => $array_of_precentage[2],
                "wired" => $array_of_precentage[3],
                "providers" => $providers
            );

            return $result_array;
        }
    }

    // public function map($row): array
    // {
    //     return [
    //         $row->zip,
    //         $row->city,
    //         $row->fiber,
    //         $row->cable,
    //         $row->dsl,
    //         $row->wired,
    //         $row->providers,
    //     ];
    // }

    // public function headings(): array
    // {
    //     return [
    //         'Zip',
    //         'City / State',
    //         'Fiber',
    //         'Cable',
    //         'DSL',
    //         'Wired',
    //         'Providers',
    //     ];
    // }
}