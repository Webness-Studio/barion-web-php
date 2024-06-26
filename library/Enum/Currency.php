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

namespace Bencurio\Barion\Enum;

/**
 * @see https://docs.barion.com/Payment-Start-v2#Currency
 */
abstract class Currency
{
    const HUF = "HUF";
    const EUR = "EUR";
    const USD = "USD";
    const CZK = "CZK";

    public static function isValid($name)
    {
        $class = new \ReflectionClass(__CLASS__);
        $constants = $class->getConstants();
        return \array_key_exists($name, $constants);
    }
}