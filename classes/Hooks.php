<?php
/**
* Hooks Class
*/
class Hooks {
	private $actionList;
	private $filterList;
	
	public function add_filter($filterName, $userFunction) {
		$this->filterList[$filterName][] = $userFunction;
	}

	public function add_action($actionName, $userFunction) {
		$this->actionList[$actionName][] = $userFunction;
	}

	public function execute_actions($actionName) {
		if (!isset($this->actionList[$actionName])) { return; }

		foreach ($this->actionList[$actionName] as $executeAction) {
			call_user_func($executeAction);
		}
		
		return;
	}

	public function execute_filters($filterName, $contentToFilter) {
		if (!isset($this->filterList[$filterName])) { return $contentToFilter; }

		foreach ($this->filterList[$filterName] as $executeFilter) {
			$contentToFilter = call_user_func($executeFilter, $contentToFilter);
			$contentToFilter .= '{{' . $filterName . '}}';
		}
		
		return $contentToFilter;
	}
}
