<?php
namespace Portfolier\Factory;

use Portfolier\Factory\AbstractFactory;
use Portfolier\Entity\Quotations\AbstractQuotations;
use Portfolier\Entity\Quotations\YahooFinanceQuotations;

class YahooFinanceFactory extends AbstractFactory
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
        $json = json_decode($r, true);

        $quotations = [];

        if ($json['chart']['error']['code'] == "Not Found") {
            $exchange = "UNKNOWN";

            $quotations[] = [
                'date' => new \DateTime("now"),
                'close' => 0,
                'high' => 0,
                'low' => 0,
                'open' => 0,
                'value' => 0
            ];
        } else {
            $exchange = $json['chart']['result'][0]['meta']['exchangeName'];

            $timestamps = $json['chart']['result'][0]['timestamp'];

            foreach ($timestamps as $key => $timestamp) {
                $date = new \DateTime();
                $date->setTimestamp($timestamp);

                $quotations[$key] = [
                    'date' => $date,
                    'close' => $json['chart']['result'][0]['indicators']['quote'][0]['close'][$key],
                    'high' => $json['chart']['result'][0]['indicators']['quote'][0]['high'][$key],
                    'low' => $json['chart']['result'][0]['indicators']['quote'][0]['low'][$key],
                    'open' => $json['chart']['result'][0]['indicators']['quote'][0]['open'][$key],
                    'value' => $json['chart']['result'][0]['indicators']['quote'][0]['volume'][$key]
                ];
            }
        }

        $yahooFinanceQuotations = new YahooFinanceQuotations();
        $yahooFinanceQuotations->setExchange($exchange);
        $yahooFinanceQuotations->setQuotations($quotations);

        return $yahooFinanceQuotations;
    }
}