<?php
/**
 * CoreShop.
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2015-2017 Dominik Pfaffenbauer (https://www.pfaffenbauer.at)
 * @license    https://www.coreshop.org/license     GNU General Public License version 3 (GPLv3)
*/

namespace CoreShop\Component\Index\Getter;

use CoreShop\Component\Index\Model\IndexColumnInterface;
use CoreShop\Component\Resource\Pimcore\Model\PimcoreModelInterface;

class BrickGetter implements GetterInterface
{
    /**
     * {@inheritdoc}
     */
    public function get(PimcoreModelInterface $object, IndexColumnInterface $config)
    {
        $columnConfig = $config->getConfiguration();
        $getterConfig = $config->getGetterConfig();

        if (isset($getterConfig['brickField']) && isset($columnConfig['className']) && isset($columnConfig['key'])) {
            $brickField = $getterConfig['brickField'];

            $brickContainerGetter = 'get'.ucfirst($brickField);
            $brickContainer = $object->$brickContainerGetter();
            $brickGetter = 'get'.ucfirst($columnConfig['className']);

            if ($brickContainer) {
                $brick = $brickContainer->$brickGetter();

                if ($brick) {
                    $fieldGetter = 'get'.ucfirst($columnConfig['key']);

                    return $brick->$fieldGetter();
                }
            }
        }

        return null;
    }
}