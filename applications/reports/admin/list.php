<?php
	_setView (__FILE__);
	_setTitle ($langArray['reports']);

	if (!isset($_POST['from_date'])) {
		$_POST['from_date'] = '';
	}

	if (!isset($_POST['to_date'])) {
		$_POST['to_date'] = '';
	}

	if (isset($_POST['report'])) {
		require_once ROOT_PATH . '/applications/reports/modeles/report.class.php';
		$reportClass = new report();

		$reportData = $reportClass->getReport();
		$depositData = $reportClass->getDeposits();
		$withdrawData = $reportClass->getWithdraws();

		$data = array();

		if (is_array($reportData)) {
			foreach($reportData as $date => $v) {
				$data[$date] = array();
			}
		}

		if (is_array($depositData)) {
			foreach($depositData as $date => $v) {
				$data[$date] = array();
			}
		}

		if (is_array($withdrawData)) {
			foreach($withdrawData as $date => $v) {
				$data[$date] = array();
			}
		}

		if (is_array($data)) {
			foreach($data as $k => $v) {
				if (isset($reportData[$k])) {
					$data[$k]['total'] = $reportData[$k]['total'];
					$data[$k]['receive'] = $reportData[$k]['receive'];
					$data[$k]['referal'] = $reportData[$k]['referal'];
					$data[$k]['win'] = $reportData[$k]['win'];
				}

				else {
					$data[$k]['total'] = 0;
					$data[$k]['receive'] = 0;
					$data[$k]['referal'] = 0;
					$data[$k]['win'] = 0;
				}

				if (isset($depositData[$k])) {
					$data[$k]['deposit'] = $depositData[$k]['deposit'];
				}

				else {
					$data[$k]['deposit'] = 0;
				}

				if (isset($withdrawData[$k])) {
					$data[$k]['withdraw'] = $withdrawData[$k]['amount'];
				}

				else {
					$data[$k]['withdraw'] = 0;
				}
			}
		}

		echo '<pre>'; var_dump($data); exit;
		
		abr('reportData', $data);
	}
?>