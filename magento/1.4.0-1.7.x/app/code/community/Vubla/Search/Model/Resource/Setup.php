<?php
/**
 * ############################################################################
 *  _           _  _            _  _  _  _  _     _                    _
 * (_)         (_)(_)          (_)(_)(_)(_)(_) _ (_)                 _(_)_
 * (_)         (_)(_)          (_) (_)        (_)(_)               _(_) (_)_
 * (_)_       _(_)(_)          (_) (_) _  _  _(_)(_)             _(_)     (_)_
 *   (_)     (_)  (_)          (_) (_)(_)(_)(_)_ (_)            (_) _  _  _ (_)
 *    (_)   (_)   (_)          (_) (_)        (_)(_)            (_)(_)(_)(_)(_)
 *     (_)_(_)    (_)_  _  _  _(_) (_)_  _  _ (_)(_) _  _  _  _ (_)         (_)
 *       (_)        (_)(_)(_)(_)  (_)(_)(_)(_)   (_)(_)(_)(_)(_)(_)         (_)
 * ############################ NOTICE OF LICENSE #############################
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * It is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you are unable to obtain a copy from the above URL, please don't
 * hesitate to contact info@vubla.com.
 *
 * @category    Vubla
 * @package     Vubla_Search
 * @copyright   Copyright (c) 2012 Vubla I/S. (http://www.vubla.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Vubla_Search_Model_Resource_Setup extends Mage_Core_Model_Resource_Setup 
{
    private final function getApiKey()
    {
        if(strpos($_SERVER['HTTP_HOST'], 'vubla.com') !== false){ return 'test_api_key'; }
        $salt = 'aa72cc2d9ae77ea226537e6c929f68bd';
        return md5(print_r($_SERVER,true) . microtime() . $salt);
    }

    public function createApiUser()
    {
        if(Mage::getModel('api/user')->loadByUsername('vubla')->getName('') == '')
        {
            $role = Mage::getModel('api/roles')
                ->setName('vubla')
                ->setPid(false)
                ->setRoleType('G')
                ->save();
            
            Mage::getModel("api/rules")
                ->setRoleId($role->getId())
                ->setResources(array('all'))
                ->saveRel();
                
            $apikey = $this->getApiKey();
           
            $user = Mage::getModel('api/user');
            $user->setData(array(
                'username' => 'vubla',
                'firstname' => 'vubla',
                'lastname' => 'vubla',
                'email' => 'support@vubla.com',
                'api_key' => $apikey,
                'api_key_confirmation' => $apikey,
                'is_active' => 1,
                'user_roles' => '',
                'assigned_user_role' => '',
                'role_name' => 'vubla',
                'roles' => array($role->getId())
            ));
            $user->save()->load($user->getId());
            
            $user->setRoleIds(array($role->getId()))
                ->setRoleUserId($user->getUserId())
                ->saveRelations();
        }
    }
}