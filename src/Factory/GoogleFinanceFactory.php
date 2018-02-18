<?php
namespace Portfolier\Factory;

use Portfolier\Factory\AbstractFactory;
use Portfolier\Entity\Quotations\AbstractQuotations;
use Portfolier\Entity\Quotations\GoogleFinanceQuotations;

class GoogleFinanceFactory extends AbstractFactory
{
    /**
     * Fabricate a Quotations entity from a response text of a Source
     *
     * @param string $r A text response of a Source
     *
     * @return Portfolier\Entity\Quotations\AbstractQuotations;
     */
    public function createQuotations(string $r): AbstractQuotations
    {
        $array = str_getcsv($r, "\n");

        if ($array[0] == "EXCHANGE%3DLON" or $array[0] == "EXCHANGE%3DUNKNOWN+EXCHANGE") {
            $exchange = "UNKNOWN";

            $quotations = [
                [
                    'date' => new \DateTime("now"),
                    'close' => 0,
                    'high' => 0,
                    'low' => 0,
                    'open' => 0,
                    'value' => 0
                ]
            ];
        } else {
            $exchange = substr($array[0], 11);

            $quotations = [];

            for ($i = 7; $i < count($array); $i++) {
                $matches = [];

                if (preg_match('/^a?(\d+),(\d+(\.\d+)?),(\d+(\.\d+)?),(\d+(\.\d+)?),(\d+(\.\d+)?),(\d+)$/', $array[$i], $matches)) {
                    $date = new \DateTime();
                    $date->format('U = Y-m-d H:i:s');

                    if (preg_match('/^a(\d+)/', $array[$i])) {
                        $timestamp = $matches[1];

                        $date->setTimestamp($timestamp);
                    } else {
                        $date->setTimestamp($timestamp);

                        $date->modify("+{$matches[1]} day");
                    }

                    $quotations[] = [
                        'date' => $date,
                        'close' => $matches[2],
                        'high' => $matches[4],
                        'low' => $matches[6],
                        'open' => $matches[8],
                        'value' => $matches[10]
                    ];
                }
            }
        }

        $googleFinanceQuotations = new GoogleFinanceQuotations();
        $googleFinanceQuotations->setExchange($exchange);
        $googleFinanceQuotations->setQuotations($quotations);

        return $googleFinanceQuotations;
    }
}