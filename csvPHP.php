<?php

$fileRep = "rep_data.csv";
$fileOrder = "orders_data.csv";

$tmp = [];
processOrders($fileOrder);
processRep($fileRep);

function processRep($fileRep) {
    $row = 0;
    $headers = [];
    global $tmp;

    if (($handle = fopen($fileRep, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $row++;
            if ($row == 1) {
                $headers = $data;
                continue;
            }

            $tmp['rep'][] = [
                $headers[0] => $data[0],
                $headers[1] => $data[1],
                $headers[2] => $data[2],
                $headers[3] => $data[3],
                $headers[4] => $data[4],
                $headers[5] => $data[5],
                $headers[6] => $data[6],
                $headers[7] => $data[7],
                $headers[8] => $data[8],
                $headers[9] => $data[9],
                $headers[10] => $data[10],
                $headers[11] => $data[11]
            ];

        }
        fclose($handle);
    }
}


function processOrders($fileOrder) {
    $row = 0;
    $headers = [];
    global $tmp;

    $lastNumCuenta = 0;
    $lastData = [];
    if (($handle = fopen($fileOrder, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $row++;
            if ($row == 1) {
                $headers = $data;
                continue;
            }

            if ($lastNumCuenta != $data[2]) {
                if ($lastNumCuenta != 0) {
                    $tmp['order'][$lastNumCuenta]['summary'] = [
                        $headers[7] => $lastData[7],
                        $headers[8] => $lastData[8],
                        $headers[9] => $lastData[9]
                    ];
                }

                $lastNumCuenta = $data[2];

                $lastData = $data;
            }

            $tmp['order'][$lastNumCuenta]['order'][] = [
                $headers[0] => $data[0],
                $headers[1] => $data[1],
                $headers[2] => $data[2],
                $headers[3] => $data[3],
                $headers[4] => $data[4],
                $headers[5] => $data[5],
                $headers[6] => $data[6]
            ];

        }

        $tmp['order'][$lastNumCuenta]['summary'] = [
            $headers[7] => $lastData[7],
            $headers[8] => $lastData[8],
            $headers[9] => $lastData[9]
        ];



        fclose($handle);
    }
}


$aux = [];
foreach ($tmp['order'] as $k => $v)
{
    $aux['order'][][$k] = $v;
}

$final['order'] = $aux['order'];
$final['rep'] = $tmp['rep'];

echo PHP_EOL.PHP_EOL;
//
// $json = json_encode($tmp);
$json = json_encode($final);
echo $json;

//print_r($headers);
//print_r($tmp);


