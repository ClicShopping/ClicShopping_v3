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


  use ClicShopping\OM\CLICSHOPPING;
  use ClicShopping\OM\Registry;
  use ClicShopping\OM\HTTP;

  class ar_admin_login {

    public $code;
    public $title;
    public $description;
    public $sort_order = 0;
    public $minutes = 5;
    public $attempts = 3;
    public $identifier;

    public function __construct() {
      $this->code = get_class($this);
      $this->group = basename(__DIR__);

      $this->title = CLICSHOPPING::getDef('module_action_recorder_admin_login_title');
      $this->description = CLICSHOPPING::getDef('module_action_recorder_admin_login_description');

      if ($this->check()) {
        $this->minutes = (int)MODULE_ACTION_RECORDER_ADMIN_LOGIN_MINUTES;
        $this->attempts = (int)MODULE_ACTION_RECORDER_ADMIN_LOGIN_ATTEMPTS;
      }
    }

    public function setIdentifier() {
      $this->identifier = HTTP::GetIpAddress();
    }

    public function canPerform($user_id, $user_name) {
      $CLICSHOPPING_Db = Registry::get('Db');

      $sql_query = 'select id
                    from :table_action_recorder
                    where module = :module
                   ';

      if (!empty($user_name)) {
        $sql_query .= ' and (user_name = :user_name or identifier = :identifier)';
      } else {
        $sql_query .= ' and identifier = :identifier';
      }

      $sql_query .= ' and date_added >= date_sub(now(),
                      interval :limit_minutes minute)
                      and success = 0
                      limit :limit_attempts
                    ';

      $Qcheck = $CLICSHOPPING_Db->prepare($sql_query);
      $Qcheck->bindValue(':module', $this->code);

      if (!empty($user_name)) {
        $Qcheck->bindValue(':user_name', $user_name);
      }

      $Qcheck->bindValue(':identifier', $this->identifier);
      $Qcheck->bindInt(':limit_minutes', $this->minutes);
      $Qcheck->bindInt(':limit_attempts', $this->attempts);
      $Qcheck->execute();

      if (count($Qcheck->fetchAll()) == $this->attempts) {
        return false;
      }

      return true;
    }

    public function expireEntries() {
      $Qdel = Registry::get('Db')->prepare('delete
                                            from :table_action_recorder
                                            where module = :module
                                            and date_added < date_sub(now(),
                                            interval :limit_minutes minute)
                                          ');
      $Qdel->bindValue(':module', $this->code);
      $Qdel->bindInt(':limit_minutes', $this->minutes);
      $Qdel->execute();

      return $Qdel->rowCount();
    }

    public function check() {
      return defined('MODULE_ACTION_RECORDER_ADMIN_LOGIN_MINUTES');
    }

    public function install() {

      $CLICSHOPPING_Db = Registry::get('Db');
      $CLICSHOPPING_Language = Registry::get('Language');

      if ($CLICSHOPPING_Language->getId() == 1) {
        $CLICSHOPPING_Db->save('configuration', [
                                          'configuration_title' => 'Veuiller indiquer le temps d\'attente pour une erreur de connexion dans la partie administration',
                                          'configuration_key' => 'MODULE_ACTION_RECORDER_ADMIN_LOGIN_MINUTES',
                                          'configuration_value' => '5',
                                          'configuration_description' => 'Veuillez indiquer le nombre de minutes d\'attente concernant une nouvelle connexion dans l\'administration.',
                                          'configuration_group_id' => '6',
                                          'sort_order' => '0',
                                          'date_added' => 'now()'
                                        ]
                      );

        $CLICSHOPPING_Db->save('configuration', [
                                          'configuration_title' => 'Veuiller indiquer le nombre de login permis pour se connecter dans la partie administration',
                                          'configuration_key' => 'MODULE_ACTION_RECORDER_ADMIN_LOGIN_ATTEMPTS',
                                          'configuration_value' => '3',
                                          'configuration_description' => 'Veuillez indiquer le nombre de login permis concernant une connexion dans l\'administration.',
                                          'configuration_group_id' => '6',
                                          'sort_order' => '0',
                                          'date_added' => 'now()'
                                        ]
                       );
      } else {

        $CLICSHOPPING_Db->save('configuration', [
                                          'configuration_title' => 'Allowed Minutes',
                                          'configuration_key' => 'MODULE_ACTION_RECORDER_ADMIN_LOGIN_MINUTES',
                                          'configuration_value' => '5',
                                          'configuration_description' => 'Number of minutes to allow login attempts to occur.',
                                          'configuration_group_id' => '6',
                                          'sort_order' => '0',
                                          'date_added' => 'now()'
                                        ]
                      );

        $CLICSHOPPING_Db->save('configuration', [
                                          'configuration_title' => 'Allowed Attempts',
                                          'configuration_key' => 'MODULE_ACTION_RECORDER_ADMIN_LOGIN_ATTEMPTS',
                                          'configuration_value' => '3',
                                          'configuration_description' => 'Number of login attempts to allow within the specified period.',
                                          'configuration_group_id' => '6',
                                          'sort_order' => '0',
                                          'date_added' => 'now()'
                                        ]
                       );
      }
    }

    public function remove() {
      return Registry::get('Db')->exec('delete from :table_configuration where configuration_key in ("' . implode('", "', $this->keys()) . '")');
    }

    public function keys() {
      return array('MODULE_ACTION_RECORDER_ADMIN_LOGIN_MINUTES', 'MODULE_ACTION_RECORDER_ADMIN_LOGIN_ATTEMPTS');
    }
  }
