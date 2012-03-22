<?php
/**
 * @version 1.0.1
 * @package JTour! 1.0.1
 * @copyright (C) 2012 joomalungma.com
 * @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
 */

defined('_JEXEC') or die('Restricted access');

class JTourTableTours extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = null;
	var $name = '';
	var $description = '';
	var $duration = 0;
	var $price;
    var $currencyId;

    var $created;
    var $modified;
    var $checked_out;
    var $checked_out_time;
    var $ordering;
    var $published;


	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
    function __construct(&$db)
    {
        parent::__construct('#__jtour_tours', 'id', $db);
    }
}