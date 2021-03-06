<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Filter;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\Andx;

/**
 * Class DateRangeFilter
 *
 * @package Sg\DatatablesBundle\Datatable\Filter
 */
class DateRangeFilter extends TextFilter
{
    //-------------------------------------------------
    // FilterInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return 'SgDatatablesBundle:Filters:filter_daterange.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function addAndExpression(Andx $andExpr, QueryBuilder $pivot, $searchField, $searchValue, &$i)
    {
        list($_dateStart, $_dateEnd) = explode(' - ', $searchValue);
        $dateStart = new \DateTime($_dateStart);
        $dateEnd = new \DateTime($_dateEnd);
        $dateEnd->setTime(23, 59, 59);

        $k = $i + 1;
        $andExpr->add($pivot->expr()->between($searchField, '?' . $i, '?' . $k));
        $pivot->setParameter($i, $dateStart->format('Y-m-d H:i:s'));
        $pivot->setParameter($k, $dateEnd->format('Y-m-d H:i:s'));
        $i += 2;

        return $andExpr;
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'daterange';
    }

    //-------------------------------------------------
    // OptionsInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        return $this;
    }
}
