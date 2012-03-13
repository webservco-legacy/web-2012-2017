<?php
/**
 * $Id$
 *
 * ACL library.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @copyright  Copyright (c) 2008 - 2012, Radu Maier
 * @license    http://www.opensource.org/licenses/bsd-license.php
 * @package    WEb
 * @subpackage WebAcl
 * @version    $Revision$
 * @since      Version 5
 * @access     public
 * @filesource
 */

/**
 * Security check.
 */
if ( !defined( '_WEB' ) )
{
	/**
	 * Stop script execution.
	 */
	exit();
}

/**
 * Acl
 * 
 * ACL library.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @package    WEb
 * @subpackage WebAcl
 *
 */
class Acl extends Library
{
	/**
	 * ACL tables.
	 *
	 * @var array
	 */
	protected $_mTable = array(
		'rules' => 'acl_rules',
		'groups' => 'acl_groups',
		'users' => 'acl_users',
		'relations' => 'acl_relations',
		'relations_list' => 'acl_object_relation',
	);

	/**
	 * Implement GetInstance() method.
	 *
	 * This is required for PHP < 5.3 compatibility ( no late static binding )
	 *
	 * @param string $className class name to instantiate
	 *
	 * @return object
	 */
	public static function GetInstance( $className = __CLASS__ )
	{
		/**
		 * Call parent method with current class name as parameter.
		 */
		return parent::_GetInstance( $className );
	}

	public function Check( $userId, $aclResource, $aclAsset = NULL )
	{
		/**
		 * Initialize result.
		 *
		 * We sart with the global deny option.
		 *
		 * @var boolean
		 */
		$check = FALSE;

		/**
		 * Check if we have an asset.
		 */
		if ( !empty( $aclAsset ) )
		{
			/**
			 * Check if we have a user rule for the asset.
			 */
			$check_user_asset = $this->CheckAcl( $userId, $aclResource, 'user', $aclAsset );

			if ( FALSE !== $check_user_asset )
			{
				/**
				 * We have a result
				 */
				return ( 'ALLOW' == $check_user_asset ) ? TRUE : FALSE;
			}
		}

		/**
		 * Check if we have a user rule for the resource.
		 */
		$check_user_resource = $this->CheckAcl( $userId, $aclResource );

		if ( FALSE !== $check_user_resource )
		{
			/**
			 * We have a result
			 */
			return ( 'ALLOW' == $check_user_resource ) ? TRUE : FALSE;
		}

		/**
		 * If we got this far, we have no specific rule for the user.
		 * Sowe get the user's relations and search there.
		 *
		 * @var array
		 */
		$user_relations = $this->GetAclRelations( $userId );

		/**
		 * Make sure we have relations.
		 */
		if ( !empty( $user_relations ) )
		{
			/**
			 * Iterate user realtions in search for a matching rule.
			 */
			foreach ( $user_relations as $dump => $relation_data )
			{
				/**
				 * Check if we have an asset.
				 */
				if ( !empty( $aclAsset ) )
				{
					/**
					 * Check if we have a group rule for the asset.
					 */
					$check_group_asset = $this->CheckAcl( $relation_data['bin.group_id'], $aclResource, 'group', $aclAsset );

					if ( FALSE !== $check_group_asset )
					{
						/**
						 * We have a result
						 */
						return ( 'ALLOW' == $check_group_asset ) ? TRUE : FALSE;
					}
				}

				/**
				 * Check if we have a group rule for the resource.
				 */
				$check_group_resource = $this->CheckAcl( $relation_data['bin.group_id'], $aclResource, 'group' );

				if ( FALSE !== $check_group_resource )
				{
					/**
					 * We have a result only if it's positive.
					 *
					 * If we have a deny rule, we will go further.
					 */
					if ( 'ALLOW' == $check_group_resource )
					{
						return TRUE;
					}
				}
			}
		}

		/**
		 * Check if we have an asset.
		 */
		if ( !empty( $aclAsset ) )
		{
			/**
			 * If we got this far with no result,
			 * the only chance left is if the user is the owner of the resource.
			 *
			 * @var string
			 */
			$resource_owner = $this->GetResourceOwner( $aclResource, $aclAsset );

			if ( $userId == $resource_owner )
			{
				/**
				 * We have a result
				 */
				return TRUE;
			}
		}

		/**
		 * Return the default result.
		 */
		return $check;
	}

	/**
	 * Checl ACL.
	 *
	 * This is the main method.
	 *
	 * It performs a single check and returns
	 * 'DENY', 'ALLOW', or FALSE
	 *
	 * @param string $aclRequest
	 * @param string $aclResource
	 * @param string $aclType
	 * @param string $aclAsset
	 *
	 * @return mixed
	 */
	public function CheckAcl( $aclRequest, $aclResource, $aclType = 'user', $aclAsset = NULL )
	{
		/**
		 * @var array
		 */
		$options['conditions'][] = array ( 'rule_type', $aclType );

		/**
		 * @var array
		 */
		$options['conditions'][] = array ( 'bin.rule_value', $aclRequest );

		/**
		 * @var array
		 */
		$options['conditions'][] = array ( 'rule_resource', $aclResource );

		if ( !empty( $aclAsset ) )
		{
			/**
			 * @var array
			 */
			$options['conditions'][] = array ( 'bin.rule_asset', $aclAsset );
		}

		/**
		 * @var array
		 */
		$options['limit'] = 1;

		/**
		 * @var mixed
		 */
		$data = Core::Database()->RetrieveOne( 'rule_action', $this->_mTable['rules'], $options );

		if ( !empty( $data ) )
		{
			return $data['rule_action'];
		}

		return FALSE;
	}

	public function GetAclRelations( $userId = NULL )
	{
		/**
		 * @var array
		 */
		$values = array( 'bin.group_id', 'group_name' , 'group_level' );

		/**
		 * @var array
		 */
		$options = array();

		if ( !empty( $userId ) )
		{
			/**
			 * @var array
			 */
			$options['conditions'][] = array ( 'bin.user_id', $userId );
		}

		/**
		 * @var mixed
		 */
		$data = Core::Database()->Retrieve( $values, $this->_mTable['relations_list'], $options );

		return $data;

	}

	/**
	 * Get resource owner
	 *
	 * @param string $aclResource
	 * @param string $aclAsset
	 */
	public function GetResourceOwner( $aclResource, $aclAsset )
	{
		/**
		 * @var array
		 */
		$asset = explode( '/', $aclResource );

		/**
		 * @var array
		 */
		$asset = array_diff( $asset, array( '' ) );

		/**
		 * @var string
		 */
		$table = strtolower( $asset[0] );

		/**
		 * @var string
		 */
		$primary_key = Core::Database()->GetPrimaryKey( $table );

		if ( 1 == Core::Config()->Get( 'database.binkey' ) )
		{
			/**
			 * @var string
			 */
			$primary_key = 'bin.' . $primary_key;
		}

		/**
		 * @var array
		 */
		$options['conditions'][] = array ( $primary_key, $aclAsset );

		/**
		 * @var array
		 */
		$options['limit'] = 1;


		$data = Core::Database()->RetrieveOne( 'bin.owner_id', $table, $options );

		if ( empty( $data ) )
		{
			return FALSE;
		}

		return $data['bin.owner_id'];
	}
}
?>