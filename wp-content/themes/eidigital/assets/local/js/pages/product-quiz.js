import { app } from '../main'; 
//empty vars
let answers;
let branchCards = [];
let questionCounter = 0;
let emailValid = '';

function resetScroll() {
	setTimeout(() => {
		// Scroll to target
		window.scrollTo({top: 0, behavior: 'smooth'});
	}, "600");
}

const fadeIn = (element) => {
	element.style.height = 'auto';
}

const fadeOut = (element) => {
	element.style.height = '0';
}

const validateEmail = (input) => {
	var validRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
	if (input.match(validRegex)) {
		return true;

	} else {
		return false;
	}
} 

const quizComponent = {
	init() {
		this.answerHandler()
		this.backHandler()
		//this.beginHandler()
		//this.resetHandler()
		this.submitHandler()
	},

	beginHandler() {
		const startButton = document.getElementById('start-button')
		const firstQuestionCard = document.getElementById('question-1')

		startButton.addEventListener('click', function handleClick(event) {
			const parentQuizCard = this.closest('.quiz-card')
			//parentQuizCard.classList.add('invisible')
			fadeOut(parentQuizCard);
			//parentQuizCard.classList.add('invisible');
			//firstQuestionCard.classList.remove('invisible');
			fadeIn(firstQuestionCard);
			questionCounter = 0;
		})
	},

	answerHandler() {
		// Get Progress Bar element. 
		let progress = document.getElementsByClassName('progress-bar')[0];
		answers = document.querySelectorAll('.answer')
		const firstQuestionCard = document.getElementById('question-1')
		const submitCard = document.getElementById('submit-card')
		const nextButtons = document.querySelectorAll('.next-button');

		if (answers.length > 0) {
			// The below for each handles when a a question button is clicked. THIS does not push you to the next question. 
			Array.from(answers).forEach((answer) => {
				answer.addEventListener('click', function handleClick(event) {							
					// Set consts based on data attributes
					const parentCard = this.closest('.quiz-card')
					const currentLabel = this.closest('li')
					const targetBranch = this.getAttribute('data-branch')
					const targetQuestion = this.getAttribute('data-question');
					const nextDiv = document.querySelectorAll('.quiz-card[data-question="' + targetQuestion + '"]');

					// Get all buttons from this question 
					const currentSelected = document.querySelectorAll('.answer.selected[data-question="' + targetQuestion + '"]')

					// IF the question has the class fork it is the first question that sets up the branching logic. 
					if (parentCard.classList.contains('fork')) {
						// Scroll back up
						resetScroll();

						// Back at fork so we need to clear all possible selected items from earlier. 	

						// Disable all next buttons
						Array.from(nextButtons).forEach((nextButton) => {
							nextButton.disabled = true;
						});

						// Get all checkboxes
						const checkboxes = document.querySelectorAll('.checkbox');

						// Uncheck all checkboxes and remove selected class
						for (var i = 0; i < checkboxes.length; i++) {
							checkboxes[i].checked = false;
							checkboxes[i].classList.remove('selected');
						}

						// Get all active labels. 
						const labelsActive = document.querySelectorAll('li.active');

						// Remove active class on labels Active. 
						for (var i = 0; i < labelsActive.length; i++) {
							labelsActive[i].classList.remove('active');
						}

						// Remove all active single question buttons
						Array.from(answers).forEach((answer) => {
							answer.classList.remove('selected');
						});

						// Set empty array for all questions. This includes the Fork question
						branchCards = [];

						// Add the intro card to the branch array
						branchCards.push(firstQuestionCard);

						// Get all questions based on the branch from the fork quesiton. 
						var questionCards = document.querySelectorAll('div[data-branch="' + targetBranch + '"]');

						// For each question add to the main array of branch questions. 
						questionCards.forEach(function (item, index) {
							branchCards.push(questionCards[index]);
						});

						// Make sure we only have one answer selected
						currentSelected.forEach((toggleSelected) => {
							toggleSelected.classList.remove('selected')
						})

						// Set this question button to selected. 
						this.classList.add('selected');

						// Add active class the parent li for group styles on buttons to work. 
						currentLabel.classList.remove('back');
						currentLabel.classList.add('active');

						// Increase question counter
						questionCounter++;

						// Show appropriate question in selected branch according to questionCounter
						//parentCard.classList.remove('active');
						parentCard.classList.add('back');
						parentCard.classList.remove('active');
						fadeOut(parentCard);
						
						if (branchCards[questionCounter]) {

							//branchCards[questionCounter].classList.remove('invisible');
							fadeIn(branchCards[questionCounter]);

							// Add active class to container to kick off entrance animation. 	
							branchCards[questionCounter].classList.remove('back');						
							branchCards[questionCounter].classList.add('active');

							// Calculate how far we are: Question you are on / total questions * 100 gets % complete.
							const bar_width = questionCounter / branchCards.length * 100;

							// Update progress bar
							if(questionCounter > 0) {								
								progress.style.width = bar_width + '%';
							}

						} else {
							// Update progress
							progress.style.width = '100%';

							// Show and hide correct question
							fadeOut(parentCard);
							fadeIn(submitCard);
						}							
					}
					// Now we are past the fork and know what branch we are on  
					else {
						if (this.classList.contains('single')) {
							// Scroll back up
							resetScroll();

							// Increase question counter
							questionCounter++;

							// Show appropriate question in selected branch according to questionCounter
							parentCard.classList.remove('active');
							parentCard.classList.add('back');
							fadeOut(parentCard);

							if (branchCards[questionCounter]) {
								fadeIn(branchCards[questionCounter]);
								// Add active class to container to kick off entrance animation. 							
								branchCards[questionCounter].classList.remove('back');
								branchCards[questionCounter].classList.add('active');

								// Calculate how far we are: Question you are on / total questions * 100 gets % complete.
								const bar_width = questionCounter / branchCards.length * 100;

								// Update progress bar
								if(questionCounter > 0) {
									progress.style.width = bar_width + '%';
								}

							} else {
								// Update profress
								progress.style.width = '100%';

								// Show and hide correct question
								fadeOut(parentCard);

								// Add active class to container to kick off entrance animation. 							
								submitCard.classList.add('active');
								fadeIn(submitCard);
							}
						}						

						// Make sure we only have one answer selected IF it is a question of type single. If it is multi select skip it. 
						currentSelected.forEach((toggleSelected) => {
							if (this.classList.contains('single')) {

								// Remove the selected class this is a single selction only question
								toggleSelected.classList.remove('selected');

								// Get all labels for only this question. 
								const labelsActive = document.querySelectorAll('.question[data-question="' + targetQuestion + '"] li.active');

								// Remove active class on labels Active. 
								for (var i = 0; i < labelsActive.length; i++) {
									labelsActive[i].classList.remove('active');
								}
							}
						})

						// If its already selected remove it, if not add selected. Multi Select is checked above. 
						if (this.classList.contains('selected')) {
							this.classList.remove('selected');
							currentLabel.classList.remove('active');
						}
						else {
							this.classList.add('selected');
							currentLabel.classList.add('active');
						}	

						if (!this.classList.contains('single')) {
						
							// Get Next Button
							const nextButton = document.querySelectorAll('.next-button[data-question="' + targetQuestion + '"]')[0];

							// IF we have at least one item selected enable the next button. 
							if (document.querySelectorAll('.answer.selected[data-question="' + targetQuestion + '"]').length > 0) {
								// Enable Next Button
								nextButton.disabled = false;
							}
							else {
								// Disable Next Button
								nextButton.disabled = true;
							}
						}
					}					
				})
			})

			// Next Question Button Logic
			Array.from(nextButtons).forEach((nextButton) => {
				nextButton.addEventListener('click', function handleClick(event) {
					// Scroll back up
					resetScroll();

					// Set consts based on data attributes
					const parentCard = this.closest('.quiz-card');
					const targetBranch = this.getAttribute('data-branch');
					const targetQuestion = this.getAttribute('data-question');
					// Get all selected answers from this question 
					const currentSelected = document.querySelectorAll('.selected[data-question="' + targetQuestion + '"]')

					// If the length is great than 0 we know we have something selected and can move on. This is a double check if it got enabled somehow. 
					if (currentSelected.length > 0) {
						// Increase question counter
						questionCounter++;

						// Show appropriate question in selected branch according to questionCounter
						parentCard.classList.remove('active');
						parentCard.classList.add('back');
						fadeOut(parentCard);

						if (branchCards[questionCounter]) {
							
							// Calculate how far we are: Question you are on / total questions * 100 gets % complete.
							const bar_width = questionCounter / branchCards.length * 100;

							// Update progress bar
							if(questionCounter > 0) {
								progress.style.width = bar_width + '%';
							}

							// Add active class to container to kick off entrance animation. 	
							branchCards[questionCounter].classList.remove('back');						
							branchCards[questionCounter].classList.add('active');
							fadeIn(branchCards[questionCounter]);

						} else {
							// Update profress
							progress.style.width = '100%';

							// Show and hide correct question
							fadeOut(parentCard);
							fadeIn(submitCard);
						}

					}


				})
			})
		}
	},

	// Handle logic for the back button is clicked. 
	backHandler() {		
		// Get Progress Bar element. 
		let progress = document.getElementsByClassName('progress-bar')[0];
		const backButtons = document.getElementsByClassName('back-button');
		const introCard = document.getElementById('intro-card');

		Array.from(backButtons).forEach((backButton) => {
			backButton.addEventListener('click', function handleClick(event) {
				// Scroll back up
				resetScroll();

				const firstQuestionCard = document.getElementById('question-1');
				const labelsActive = document.querySelectorAll('li.active');
				const checkboxes = document.querySelectorAll('.checkbox');
				const parentQuizCard = this.closest('.quiz-card');
				const cardAnswers = parentQuizCard.querySelectorAll('button.answer');
				const nextButtons = document.querySelectorAll('.next-button');

				// Decrease question counter.
				questionCounter--;

				// Hide the current question. 
				//parentQuizCard.classList.add('back');
				parentQuizCard.classList.remove('active');
				
				fadeOut(parentQuizCard);

				// IF we are below 0 we want to show the intro card.
				if (questionCounter < 1) {
					// Uncheck all checkboxes and remove selected class
					for (var i = 0; i < checkboxes.length; i++) {
						checkboxes[i].checked = false;
						checkboxes[i].classList.remove('selected');
					}

					// Remove active class on labels Active. 
					for (var i = 0; i < labelsActive.length; i++) {
						labelsActive[i].classList.remove('active');
					}

					// Disable all next buttons
					Array.from(nextButtons).forEach((nextButton) => {
						nextButton.disabled = true;
					});

					// Show Intro Card
					//firstQuestionCard.classList.remove('invisible');
					//firstQuestionCard.classList.add('back');
					firstQuestionCard.classList.add('active');
					fadeIn(firstQuestionCard);

					// Update progress
					progress.style.width = '0%';

					// Reset counter
					questionCounter = 0;
				} else {
					// Show the previous question					
					//branchCards[questionCounter].classList.remove('invisible');
					branchCards[questionCounter].classList.add('active');
					//branchCards[questionCounter].classList.add('back');
					fadeIn(branchCards[questionCounter]);

					// Calculate how far we are: Question you are on / total questions * 100 gets % complete.
					const bar_width = questionCounter / branchCards.length * 100;

					// Update progress bar
					if(questionCounter > 0) {
						progress.style.width = bar_width + '%';
					}
				}

			})
		})
	},

	resetHandler() {
		// Get Progress Bar element. 
		let progress = document.getElementsByClassName('progress')[0];
		let progressBar = document.getElementsByClassName('progress-bar')[0];
		const firstQuestionCard = document.getElementById('question-1');
		const cardAnswers = document.querySelectorAll('button.answer');
		const introCard = document.getElementById('intro-card');
		const resetButton = document.getElementById('restart-quiz');
		const resultsTargetCard = document.getElementById('results-card');
		const quizCards = document.querySelectorAll('button.answer');
		const checkboxes = document.querySelectorAll('.checkbox');
		const nextButtons = document.querySelectorAll('.next-button');
		const loadingEl = document.getElementById('loading');	

		resetButton.addEventListener('click', function handleClick(event) {
			// Scroll back to top
			resetScroll()

			// Get all active labels. 
			const labelsActive = document.querySelectorAll('li.active');

			// Uncheck all checkboxes. 
			for (var i = 0; i < checkboxes.length; i++) {
				checkboxes[i].checked = false;
				checkboxes[i].classList.remove('selected');
			}

			// Remove active class on labels. 
			for (var i = 0; i < labelsActive.length; i++) {
				labelsActive[i].classList.remove('active');
			}

			// Disable all next buttons
			Array.from(nextButtons).forEach((nextButton) => {
				nextButton.disabled = true;
			});

			// clear selected state of all form elements
			cardAnswers.forEach((answer) => {
				answer.classList.remove('selected')
				answer.checked = false;
			})

			// Rest background image
			let quizBody = document.getElementById('quiz-body');
			quizBody.style.backgroundImage = 'none';

			// Hide loader
			fadeOut(loadingEl);	

			// Hide results target quesiton
			resultsTargetCard.classList.remove('active');
			fadeOut(resultsTargetCard);

			// Reset counter
			questionCounter = 0;

			// Show the Progress bar
			progress.style.display = 'block';
			fadeIn(progress);

			// Show the First Question
			firstQuestionCard.classList.add('active');
			fadeIn(firstQuestionCard);

			// Reset progress
			progressBar.style.width = '0%';
		})
	},

	submitHandler() {
		// Hold on to this
		let app = this;

		// Get Progress Bar element. 
		let progress = document.getElementsByClassName('progress')[0];
		const resultsTargetCard = document.getElementById('results-card')
		const submitButton = document.getElementById('submit-quiz')
		const submitCard = document.getElementById('submit-card')
		const loadingEl = document.getElementById('loading');		


		submitButton.addEventListener('click', function handleClick(event) {
			const email = document.getElementById("email").value;
			const questionsArr = []
			const resultsArr = []
			const postUrl = window.location.origin + '/wp-admin/admin-ajax.php?action=js_fetch_query';

			// Show loader
			fadeIn(loadingEl);

			// IF there is something in the email field we need to validate it. 
			if(email) {
				emailValid = validateEmail(email);
			}
			else {
				emailValid = false;
			}

			// IF we have a valid email or they skipped adding an email. 
			if(emailValid) {
				// Fade out progress bar
				progress.style.display = 'none';
				fadeOut(progress);	

				// Fade out last screen while we wait for results. 	
				submitCard.classList.remove('active');
				fadeOut(submitCard);

				// On submit, get only the button that is selected AND
				// set as the results trigger AND
				// add those to the payload 
				answers.forEach((answerButton) => {
					// if (toggleButton.classList.contains('selected') && toggleButton.getAttribute("data-results") != null) {
					if (answerButton.classList.contains('selected')) {
						let newAnswerNode;
						if (answerButton.getAttribute('data-results') != null) {
							newAnswerNode = {
								branch: answerButton.getAttribute('data-branch'),
								question_number: answerButton.getAttribute('data-question'),
								results: answerButton.getAttribute('data-results')
							};
							resultsArr.push(newAnswerNode)
						}
						newAnswerNode = {
							branch: answerButton.getAttribute('data-branch'),
							question_number: answerButton.getAttribute('data-question'),
							question: answerButton.getAttribute('data-question-title'),
							answer: answerButton.getAttribute('data-question-answer')
						}
						questionsArr.push(newAnswerNode)
					}
				})

				const data = new FormData()
				const postData = { questions: questionsArr, results: resultsArr, email: email }
				data.append('answers', postData)

				fetch(
					postUrl, {
					method: 'POST',
					headers: {
						'Accept': 'text/plain',
						'Content-Type': 'text/plain'
					},
					body: JSON.stringify({ data: postData }),
				})
				.then(response => response.text())
				.then(html => {
					
					// Add html to container
					resultsTargetCard.querySelector('.results-stage').innerHTML = html;

					// Show content container
					resultsTargetCard.classList.add('active');
					fadeIn(resultsTargetCard);

					// Hide loader icon
					//fadeOut(loadingEl);	
					loadingEl.style.visibility = "hidden";

					// Scroll back up
					resetScroll();

					// Pick up the background image from the results. 
					const backgroundImage = document.querySelectorAll('#results-card picture')[0];
					const backgroundImagePath = backgroundImage.getAttribute('data-background');
					const backgroundImagePathMobile = backgroundImage.getAttribute('data-background-mobile');

					// style the quiz body
					let quizBody = document.getElementById('quiz-body');
					
					if (window.innerWidth > 768) {
						quizBody.style.backgroundImage = "url('" + backgroundImagePath + "')";
					}
					else {
						quizBody.style.backgroundImage = "url('" + backgroundImagePathMobile + "')";
					}

					

					// When resizing the window, update background image if less than 768
					window.addEventListener('resize', () => {	
						// Only want to show background image on the results page. 
						if(resultsTargetCard.classList.contains('active')) {					
							if (window.innerWidth > 768) {
								quizBody.style.backgroundImage = "url('" + backgroundImagePath + "')";
							}
							else {
								quizBody.style.backgroundImage = "url('" + backgroundImagePathMobile + "')";
							}
						}
						else {
							quizBody.style.backgroundImage = 'none';
						}
					});
					
					// Call reset JS
					app.resetHandler();

				})
				.catch(function (err) {
					console.log('Failed to fetch page: ', err)
				}) // end fetch
			}
			else {
				// Hide loader icon
				fadeOut(loadingEl);	
				
				const message = document.getElementById('form-messages');
				message.innerHTML = '<p class="text-14">Please enter a valid email</p>';
				message.classList.remove('invisible');
				fadeIn(message);
			}
		})
	}
}

// The page is fully loaded.
addEventListener('load', (event) => {
	// Add JS that requires the full page to be loaded.
	quizComponent.init()
})

export { quizComponent }