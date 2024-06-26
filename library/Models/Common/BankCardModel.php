<?php

/**
 * Copyright 2016 Barion Payment Inc. All Rights Reserved.
 * <p/>
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * <p/>
 * http://www.apache.org/licenses/LICENSE-2.0
 * <p/>
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Bencurio\Barion\Models\Common;

use Bencurio\Barion\Helpers\iBarionModel;

class BankCardModel implements iBarionModel
{
    public $MaskedPan;
    public $BankCardType;
    public $ValidThruYear;
    public $ValidThruMonth;

    function __construct()
    {
        $this->MaskedPan = "";
        $this->BankCardType = "";
        $this->ValidThruYear = "";
        $this->ValidThruMonth = "";
    }

    public function fromJson($json)
    {
        if (!empty($json)) {
            $this->MaskedPan = \Bencurio\Barion\Helpers\BarionHelper::jget($json, 'MaskedPan');
            $this->BankCardType = \Bencurio\Barion\Helpers\BarionHelper::jget($json, 'BankCardType');
            $this->ValidThruYear = \Bencurio\Barion\Helpers\BarionHelper::jget($json, 'ValidThruYear');
            $this->ValidThruMonth = \Bencurio\Barion\Helpers\BarionHelper::jget($json, 'ValidThruMonth');
        }
    }
}