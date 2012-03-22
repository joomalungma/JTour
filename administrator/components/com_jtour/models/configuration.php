<?php
/**
 * @version 1.0.1
 * @package JTour! 1.0.1
 * @copyright (C) 2012 joomalungma.com
 * @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class JTourModelConfiguration extends JModel
{
	
	function __construct()
	{
		parent::__construct();
        $this->db = JFactory::getDbo();
	}
	
	function save()
	{
        $app	= JFactory::getApplication();
		$config = jtourHelper::getConfig();
		$in = new JInput();
        $vars = array(
            'moderatorEmail'    => 'string',
            'currency'          => 'array',
            'currencyDefaultIndex'=> 'int'
        );
		$post = $in->getArray($vars);

		
		if (isset($post['currencyDefaultIndex']) && count($post['currency']))
		{
            $data = array();
            for($i =0; $i < count($post['currency']['name']);$i++)
            {
                $data[$post['currency']['name'][$i]] = $post['currency']['rate'][$i];
                if($i == $post['currencyDefaultIndex']) $defaultCurr = $post['currency']['name'][$i];
            }
            $data = json_encode($data);

            $this->db->setQuery("UPDATE #__jtour_configuration SET `value`='".$data."' WHERE `name`='currency'");
			$this->db->query();
            $this->db->setQuery("UPDATE #__jtour_configuration SET `value`='".$defaultCurr."' WHERE `name`='currencyDefault'");
            $this->db->query();
        }

        if (isset($post['moderatorEmail']))
        {
            if(JTourValidation::email($post['moderatorEmail']))
            {
                $this->db->setQuery("UPDATE #__jtour_configuration SET `value`='".$post['moderatorEmail']."' WHERE `name`='moderatorEmail'");
                $this->db->query();
            }
            else $app->enqueueMessage(JText::_('JTOUR_EMAIL_WRONG'), 'warning');
        }

        jtourHelper::readConfig(true);
	}
}
?>