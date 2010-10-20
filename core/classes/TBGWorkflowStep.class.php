<?php

	/**
	 * Workflow step class
	 *
	 * @author Daniel Andre Eikeland <zegenie@zegeniestudios.net>
	 * @version 3.0
	 * @license http://www.opensource.org/licenses/mozilla1.1.php Mozilla Public License 1.1 (MPL 1.1)
	 * @package thebuggenie
	 * @subpackage core
	 */

	/**
	 * Workflow step class
	 *
	 * @package thebuggenie
	 * @subpackage core
	 */
	class TBGWorkflowStep extends TBGIdentifiableClass
	{

		/**
		 * The workflow description
		 *
		 * @var string
		 */
		protected $_description = null;

		protected $_is_editable = null;

		protected $_is_closed = null;

		protected $_linked_status = null;

		/**
		 * The associated workflow object
		 *
		 * @var TBGWorkflow
		 */
		protected $_workflow = null;

		public function __construct($id, $row)
		{
			if (!is_numeric($id))
			{
				throw new Exception('Please specify a valid workflow step id');
			}
			if ($row === null)
			{
				$row = TBGWorkflowStepsTable::getTable()->getByID($id);
			}

			if (!$row instanceof B2DBRow)
			{
				throw new Exception('The specified file id does not exist');
			}

			$this->_itemid = $row->get(TBGWorkflowStepsTable::ID);
			$this->_name = $row->get(TBGWorkflowStepsTable::NAME);
			$this->_description = $row->get(TBGWorkflowStepsTable::DESCRIPTION);
			$this->_is_editable = (bool) $row->get(TBGWorkflowStepsTable::EDITABLE);
			$this->_is_closed = (bool) $row->get(TBGWorkflowStepsTable::IS_CLOSED);
			$this->_workflow = TBGContext::factory()->TBGWorkflow($row->get(TBGWorkflowStepsTable::WORKFLOW_ID));
			$this->_linked_status = $row->get(TBGWorkflowStepsTable::STATUS_ID);
		}

		/**
		 * Returns the workflows description
		 *
		 * @return string
		 */
		public function getDescription()
		{
			return $this->_description;
		}

		/**
		 * Set the workflows description
		 *
		 * @param string $description
		 */
		public function setDescription($description)
		{
			$this->_description = $description;
		}

		/**
		 * Return the workflow
		 *
		 * @return TBGWorkflow
		 */
		public function getWorkflow()
		{
			return $this->_workflow;
		}

		/**
		 * Whether this is a step in the builtin workflow that cannot be
		 * edited or removed
		 *
		 * @return boolean
		 */
		public function isCore()
		{
			return ($this->getWorkflow()->getID() == 1);
		}

		/**
		 * Return this steps linked status if any
		 * 
		 * @return TBGStatus
		 */
		public function getLinkedStatus()
		{
			if (is_numeric($this->_linked_status))
			{
				try
				{
					$this->_linked_status = TBGContext::factory()->TBGStatus($this->_linked_status);
				}
				catch (Exception $e)
				{
					$this->_linked_status = null;
				}
			}
			return $this->_linked_status;
		}

		/**
		 * Whether or not this step is linked to a specific status
		 *
		 * @return boolean
		 */
		public function hasLinkedStatus()
		{
			return ($this->getLinkedStatus() instanceof TBGStatus);
		}

	}