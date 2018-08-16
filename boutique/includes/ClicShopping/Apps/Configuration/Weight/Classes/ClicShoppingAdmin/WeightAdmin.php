<?php
/**
 *
 *  @copyright 2008 - https://www.clicshopping.org
 *  @Brand : ClicShopping(Tm) at Inpi all right Reserved
 *  @Licence GPL 2 & MIT
 *  @licence MIT - Portion of osCommerce 2.4 
 *
 *
 */


  namespace ClicShopping\Apps\Configuration\Weight\Classes\ClicShoppingAdmin;

  use ClicShopping\OM\Registry;

  class WeightAdmin extends \ClicShopping\Apps\Configuration\Weight\Classes\Shop\Weight {

    protected $weight_classes = [];
    protected $precision = 2;

    public function __construct($precision = null) {
    }

    public static function getTitle($id, $language_id = null) {
      return parent::getTitle($id, $language_id);
    }

    public static function getClasses() {
     return parent::getClasses();
    }

    public function display($value, $class) {
      return parent::display($value, $class);
    }
/**
 * Drop down of the class title
 *
 * @param string $parameters, $selected
 * @return string $select_string, the drop down of the title class
 * @access public
 *
 */
    public static function getClassesPullDown() {
      $CLICSHOPPING_Language = Registry::get('Language');
      $CLICSHOPPING_Db = Registry::get('Db');

      $Qclasses = $CLICSHOPPING_Db->prepare('select weight_class_id, 
                                                    weight_class_title 
                                              from :table_weight_classes 
                                              where language_id = :language_id 
                                              order by weight_class_title
                                            ');
      $Qclasses->bindInt(':language_id', $CLICSHOPPING_Language->getID());
      $Qclasses->execute();

      while ($Qclasses->fetch() !== false) {
        $classes[] = ['id' => $Qclasses->valueInt('weight_class_id'),
                       'text' => $Qclasses->value('weight_class_title')
                     ];
      }

      return $classes;
    }
  }



