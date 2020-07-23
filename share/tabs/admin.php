<?php
	class AdminTab extends AdaTab 
	{
		function __construct($divName, $tabText) {
			parent::__construct($divName, $tabText);
		}

		protected function PrintTabContent() {
			$this->PrintAdmin();
		}

		private function PrintAdmin() {
			echo "<form name='hash_form' onSubmit='GetHash(); return false;'>";
			echo "<h3>Get Password Hash</h3>";	
			echo "<input type='password' id='HashBox' name='hash'/>";
			echo "<span id='HashSpan'></span>";
			echo "</form>";
		}
	}
?>
