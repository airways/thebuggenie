<?php

	/**
	 * Teams table
	 *
	 * @author Daniel Andre Eikeland <zegenie@zegeniestudios.net>
	 * @version 2.0
	 * @license http://www.opensource.org/licenses/mozilla1.1.php Mozilla Public License 1.1 (MPL 1.1)
	 * @package thebuggenie
	 * @subpackage tables
	 */

	/**
	 * Teams table
	 *
	 * @package thebuggenie
	 * @subpackage tables
	 */
	class TBGTeamsTable extends B2DBTable 
	{

		const B2DBNAME = 'teams';
		const ID = 'teams.id';
		const SCOPE = 'teams.scope';
		const TEAMNAME = 'teams.teamname';
		
		public function __construct()
		{
			parent::__construct(self::B2DBNAME, self::ID);
			
			parent::_addVarchar(self::TEAMNAME, 50);
			parent::_addForeignKeyColumn(self::SCOPE, B2DB::getTable('TBGScopesTable'), TBGScopesTable::ID);
		}

		public function loadFixtures($scope_id)
		{
			$i18n = TBGContext::getI18n();

			$crit = $this->getCriteria();
			$crit->addInsert(TBGTeamsTable::TEAMNAME, 'Staff members');
			$crit->addInsert(TBGTeamsTable::SCOPE, $scope_id);
			$this->doInsert($crit);

			$crit = $this->getCriteria();
			$crit->addInsert(TBGTeamsTable::TEAMNAME, 'Developers');
			$crit->addInsert(TBGTeamsTable::SCOPE, $scope_id);
			$this->doInsert($crit);

			$crit = $this->getCriteria();
			$crit->addInsert(TBGTeamsTable::TEAMNAME, 'Team leaders');
			$crit->addInsert(TBGTeamsTable::SCOPE, $scope_id);
			$this->doInsert($crit);
		}

		public function getAll($scope = null)
		{
			$scope = ($scope === null) ? TBGContext::getScope()->getID() : $scope;
			$crit = $this->getCriteria();
			$crit->addWhere(self::SCOPE, $scope);
			
			$res = $this->doSelect($crit);
			
			return $res;
		}
		
	}