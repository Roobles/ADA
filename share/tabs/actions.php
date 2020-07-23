<?php
	// Options

	class ActionsTab extends AdaTab
	{
                function __construct($divName, $tabText) {
			parent::__construct($divName, $tabText);
		}

		protected function PrintTabContent() {
			$this->PrintActions();
		}

		private function PrintActions() {
			echo "<h3>Options</h3>";
			$this->PrintToggledOptions();

			echo "<h3>Outputs</h3>";
			$this->PrintOutputs();
		}

		private function PrintToggledOptions() {
			$randomArgs	= Array(
				"checkId"	=> "RandomCheck",
				"checkHandler"	=> "Random()",
				"checkLabel"	=> "Random"
			);

			$repeatArgs	= Array(
				"checkId"	=> "RepeatCheck",
				"checkHandler"	=> "Repeat()",
				"checkLabel"	=> "Repeat"
			);

			$checkboxArgs = Array(
				$randomArgs,
				$repeatArgs
			);

			PrintCheckboxList($checkboxArgs);
		}

		private function PrintOutputs() {
			$outputs 	= GetOutputs();
			$checkboxArgs	= Array();

			foreach($outputs as $output) {
				$checkId 	= sprintf("Output%d", 	$output[0]);
				$checkHandler	= sprintf("Output(%d)", $output[0]);
				$checkLabel	= $output[2];

				array_push($checkboxArgs, Array( 
					"checkId" 	=> $checkId,
					"checkHandler"	=> $checkHandler,
					"checkLabel"	=> $checkLabel
				));
			}

			PrintCheckboxList($checkboxArgs);
		}
	}
?>

