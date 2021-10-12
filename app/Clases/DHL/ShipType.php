<?php

namespace App\Clases\DHL;

class ShipType
{
    private string $postal_code;
    private string $CountryCode;
    public function __construct($cp, $CountryCode)
    {
        if (!isset($CountryCode))
        {
            $CountryCode = "MX";
        }
        $this->postal_code=$cp;
        $this->CountryCode = $CountryCode;
    }

    public function Ship():array{
        //$db=new EntidadBase();
        //$cp=$db->getby("CodigosPostales","CP",$this->postal_code);
        $city="City";

        return
            [
                "City" => $city,
                "PostalCode" => $this->postal_code,
                "CountryCode" => $this->CountryCode
            ];
    }

    public function Contact($PersonName, $CompanyName, $PhoneNumber,$EmailAddress):array{
        return [
                "PersonName" => $PersonName,
                "CompanyName"=> $CompanyName,
                "PhoneNumber"=> $PhoneNumber,
                "EmailAddress"=> $EmailAddress
            ];
    }

    public function Address($StreetLines, $City, $PostalCode , $CountryCode):array
    {
        return [
                "StreetLines"=> $StreetLines,
                "City"=> $City,
                "PostalCode"=> $PostalCode,
                "CountryCode"=> $CountryCode
            ];
    }
}