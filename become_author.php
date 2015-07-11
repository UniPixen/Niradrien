	if(isset($_POST['submit'])) {				
		if($_SESSION['member']['author'] == '') {
			require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
			$cms = new members(); 	
			$setQuery = '';
		
			if (isset($_POST['author'])) {
					$setQuery .= " , author = 'true' ";
				}

				else {
					$setQuery .= " , author = 'true' ";
				}
			
			refresh('/member/dashboard/');
			if ($setQuery != '') {
				$mysql->query("
					UPDATE members
					SET $setQuery
					WHERE member_id = " . intval($id) . "
					LIMIT 1
				", __FUNCTION__ );
			}

			return true;
		}
		else {
			addErrorMessage($langArray['error_not_valid_email'], '', 'error');
		    return false;
		}

}
