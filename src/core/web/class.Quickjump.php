<?php

class Quickjump
{

    private $collection;

    private $getter;

    private $link;

    private $tpl;

    private $lblArray;

    private $initialized;

    private $skala;

    const ALPHA = array(
        "A",
        "B",
        "C",
        "D",
        "E",
        "F",
        "G",
        "H",
        "I",
        "J",
        "K",
        "L",
        "M",
        "N",
        "O",
        "P",
        "Q",
        "R",
        "S",
        "T",
        "U",
        "V",
        "W",
        "X",
        "Y",
        "Z"
    );

    const PLZ = array(
        1,
        2,
        3,
        4,
        5,
        6,
        7,
        8,
        9
    );

    // private $numeric = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 78, 79, 80, 81, 82, 83, 84, 85, 86, 87, 88, 89, 90, 91, 92, 93, 94, 95, 96, 97, 98, 99, 100, 101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122, 123, 124, 125, 126, 127, 128, 129, 130, 131, 132, 133, 134, 135, 136, 137, 138, 139, 140, 141, 142, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 153, 154, 155, 156, 157, 158, 159, 160, 161, 162, 163, 164, 165, 166, 167, 168, 169, 170, 171, 172, 173, 174, 175, 176, 177, 178, 179, 180, 181, 182, 183, 184, 185, 186, 187, 188, 189, 190, 191, 192, 193, 194, 195, 196, 197, 198, 199, 200);
    const NUMERIC = array(
        1,
        2,
        3,
        4,
        5,
        6,
        7,
        8,
        9,
        10,
        11,
        12,
        13,
        14,
        15,
        16,
        17,
        18,
        19,
        20,
        21,
        22,
        23,
        24,
        25,
        26,
        27,
        28,
        29,
        30,
        31,
        32,
        33,
        34,
        35,
        36,
        37,
        38,
        39,
        40,
        41,
        42,
        43,
        44,
        45,
        46,
        47,
        48,
        49,
        50,
        51,
        52,
        53,
        54,
        55,
        56,
        57,
        58,
        59,
        60,
        61,
        62,
        63,
        64,
        65,
        66,
        67,
        68,
        69,
        70,
        71,
        72,
        73,
        74,
        75,
        76,
        77,
        78,
        79,
        80,
        81,
        82,
        83,
        84,
        85,
        86,
        87,
        88,
        89,
        90,
        91,
        92,
        93,
        94,
        95,
        96,
        97,
        98,
        99,
        100
    );

    public function __construct(ArrayList $c, $getterName, $linkPattern, $skala, $labelArray = null)
    {
        $this->collection = $c;
        $this->getter = $getterName;
        $this->link = $linkPattern;
        $this->lblArray = $labelArray;
        $this->initialized = false;
        $this->skala = $skala;
        $this->tpl = new BufferedTemplate("quickjump.tpl");
    }

    private function init()
    {
        $scale = array();
        if ($this->skala == "ALPHA") {
            $scale = Quickjump::ALPHA;
            $truncate = true;
        } elseif ($this->skala == "PLZ") {
            $scale = Quickjump::PLZ;
            $truncate = true;
        } elseif ($this->skala == "NUMERIC") {
            $scale = Quickjump::NUMERIC;
            $truncate = false;
        } elseif ($this->skala == "FREE") {
            $scale = array();
            $truncate = false;
        } else {
            throw new IllegalArgumentException("Skala not supported, choosed ALHPA, PLZ, FREE or NUMERIC");
        }
        
        $firstChoose = true;
        $exists = array();
        foreach ($this->collection as $objekt) {
            $tmpIndex = call_user_func(array(
                $objekt,
                $this->getter
            ));
            
            if ($truncate) {
                $tmpIndex = substr($tmpIndex, 0, 1);
            }
            
            if (! isset($exists[$tmpIndex])) {
                $exists[$tmpIndex] = $tmpIndex;
            }
        }
        
        if ($this->skala == "FREE") {
            $scale = $exists;
        }
        
        foreach ($scale as $entry) {
            $link = "#";
            $class = "notexists";
            if (isset($exists[$entry])) {
                $class = "exists";
                $link = str_replace("<!--Index-->", $entry, $this->link);
            }
            if ($this->lblArray != null && isset($this->lblArray[$entry])) {
                $entry = $this->lblArray[$entry];
            }
            
            if ($this->lblArray == null || $this->lblArray != null && $class == "exists") {
                $this->tpl->replace("IndexLabel", $entry);
                $this->tpl->replace("IndexClass", $class);
                $this->tpl->replace("Link", $link);
                $this->tpl->next();
            }
        }
        
        $link = str_replace("<!--Index-->", "all", $this->link);
        $this->tpl->replace("IndexLabel", "Alle");
        $this->tpl->replace("IndexClass", "exists");
        $this->tpl->replace("Link", $link);
        $this->tpl->next();
        
        $this->initialized = true;
    }

    public function getOutput()
    {
        if ($this->initialized == false) {
            $this->init();
        }
        return $this->tpl->getOutput();
    }

    public function anzeigen()
    {
        if ($this->initialized == false) {
            $this->init();
        }
        return $this->tpl->anzeigen();
    }
}
