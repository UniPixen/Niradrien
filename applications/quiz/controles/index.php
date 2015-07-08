<?php
	_setView(__FILE__);

	$showQuiz = false;

	if ($showQuiz) {
		if (!check_login_bool()) {
			refresh('/login');
		}

		require_once ROOT_PATH . '/applications/quiz/modeles/quiz.class.php';
		$quizClass = new quiz();

		require_once ROOT_PATH . '/applications/quiz/modeles/answers.class.php';
		$answersClass = new answers();

		$questions = $quizClass->getAll(0, 0, '', 'RAND()');
		abr('questions', $questions);

		$answers = $answersClass->getAll(0, 0, '', true);
		abr('answers', $answers);

		if ($_SESSION['member']['author'] != 'false') {
			refresh('/author');
		}

		if (isset($_POST['submit'])) {
			$rightAnswers = 0;
			
			if (isset($_POST['answers']) && is_array($_POST['answers'])) {
				foreach ($_POST['answers'] as $question=>$answer) {
					if (isset($answers[$question][$answer]) && $answers[$question][$answer]['right'] == 'true') {
						$rightAnswers++;
					}
				}
			}

			if ($rightAnswers > 0 && count($questions) == $rightAnswers) {
				$_SESSION['member']['author'] = 'true';

				require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
				$membersClass = new members();

				$membersClass->updateAuthor($_SESSION['member']['member_id'], 'true');

				refresh('/members/dashboard/', $langArray['complete_score_quiz'], 'complete');
			}

			else {
				addErrorMessage(langMessageReplace($langArray['error_quiz'], array('RIGHT' => $rightAnswers, 'TOTAL' => count($questions))), '', 'error');
			}
		}
	}

	else {
		include_once (ROOT_PATH . '/applications/error/controles/index.php');
	}
?>