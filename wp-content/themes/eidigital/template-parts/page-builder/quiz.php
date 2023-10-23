<?php
$data = $args['data'];
$introduction = $data['introduction_card'];
$questions = get_field('questions', $data['quiz'][0]->ID);
$intro = array_shift($questions);
$branches = array();
$question_counter = 1;
?>
<div id="loading" class="invisible h-0 absolute top-0 bottom-0 left-0 right-0 m-auto h-4 w-60 z-20 text-center"><svg class="mx-auto" xmlns="http://www.w3.org/2000/svg" version="1.0" width="64" height="64" viewBox="0 0 128 128" xml:space="preserve"><rect width="100%" height="100%" fill="#FFF"/><g><path d="M64 128A64 64 0 0 1 18.34 19.16L21.16 22a60 60 0 1 0 52.8-17.17l.62-3.95A64 64 0 0 1 64 128z"/><animateTransform attributeName="transform" type="rotate" from="0 64 64" to="360 64 64" dur="1800ms" repeatCount="indefinite"/></g></svg></div>
<section id="progress-bar" class="container progress max-w-3xl mx-auto mt-24 md:mt-28">
	<div class="progress-wrapper w-full h-2 mb-4 bg-gray rounded-full">
		<div class="progress-bar h-2 bg-black rounded-full" style="width: 0%"></div> 
	</div> 
</section>
<section id="quiz-body" class="overflow-hidden quiz relative mx-auto pt-2 md:pt-4 pb-20 bg-cover bg-center bg-no-repeat">
	<div class="max-w-3xl mx-auto overflow-hidden">
		<div class="fork quiz-card rounded max-w-3xl mx-auto w-full active" data-branch="0" data-question="<?= $question_counter ?>" id="question-<?= $question_counter ?>">
			<div class="max-w-lg mx-auto">
				<h2 class="font-title text-black text-24 leading-30 md:leading-44 text-center uppercase mb-4"><?= $intro['question_copy'] ?></h2>
				<p class="text-black text-center text-14 mb-8">Choose One</p>
			</div>
			<ul class="answer-list px-1 md:px-8">
				<?php foreach ($intro['answers'] as $answer) : ?>
					<li class="group flex items-center justify-start w-full py-3 md:py-6 px-5 border-solid bg-white border border-black rounded-xl mb-4 md:mb-8 answer text-black hover:bg-black hover:text-white group-[.active]:bg-black group-[.active]:text-white cursor-pointer" data-question-type="<?= $intro['question_type'] ?>" data-question="<?= $question_counter ?>" data-branch="<?= $answer['branch'] ?>" data-question-title="<?= $intro['question_copy'] ?>" data-question-answer="<?= $answer['answer'] ?>">
						<?php if ($answer['answer_image']) : ?>
								<picture class="mr-6">
								<source srcset="<?= $answer['answer_image']['sizes']['card'] ?>">
								<img src="data:," class="" alt="<?= $answer['answer_image']['alt'] ?>">
							</picture>
						<?php endif; ?>
						<?php if ($answer['emoji_icon']) : ?>
							<span class="mr-6 text-35"><?= $answer['emoji_icon'] ?></span>						
						<?php endif; ?>
						<span class="text-left leading-28"><?= $answer['answer'] ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php $question_counter++ ?>

		<?php foreach ($questions as $question) : ?>
			<div class="branch-<?= $question['branch'] ?> quiz-card question rounded h-0 max-w-3xl mx-auto w-full" data-branch="<?= $question['branch'] ?>" data-question="<?= $question_counter ?>" id="question-<?= $question_counter ?>">
				<div class="max-w-lg mx-auto">
					<h2 class="font-title text-black text-24 leading-30 md:leading-44 text-center uppercase mb-4"><?= $question['question_copy'] ?></h2>
					<?php if($question['question_type'] == 'single'): ?>
						<?php $type_copy = 'Choose one'; ?>
					<?php else: ?>
						<?php $type_copy = 'Choose all that apply'; ?>
					<?php endif; ?>
					
					<p class="text-black text-center text-14 mb-8"><?= $type_copy ?></p>
				</div>
				<ul class="answer-list px-1 md:px-8">
					<?php foreach ($question['answers'] as $answer) : ?>
						<?php
						// Set single question boolean to false. IF true means its a single question and we will not show the next button. Logic for this is below. 
						$show_next = true;

						// Set results array
						$results = array();

						// IF this question is set to trigger results we want to poupulate the results array with post IDs. 
						if ($answer['triggers_results'] == 1 && is_array($answer['results'])) :
							// There could be more than 1 post for the results add them to the array
							foreach ($answer['results'] as $result) :
								array_push($results, $result->ID);
							endforeach;
						endif;
						
						// Get question type single vs multi = radio vs checkbox
						if($question['question_type'] == 'single'):
							$question_type = 'radio';
							$show_next = false;
						else: 
							$question_type = 'checkbox';
						endif;
						?>
						<li class="answer group flex items-center justify-start w-full py-3 md:py-6 px-5 border-solid bg-white text-black border border-black rounded-xl mb-4 md:mb-8 [&.active]:bg-black [&.active]:text-white cursor-pointer <?= $question['question_type'] ?>" data-question-type="<?= $question['question_type'] ?>" data-question="<?= $question_counter ?>" data-branch="<?= $answer['branch'] ?>" data-question-title="<?= $question['question_copy'] ?>" data-question-answer="<?= $answer['answer'] ?>" <?php if (!empty($results)) : ?> data-results="<?php echo implode(",", $results); ?>" <?php endif; ?>>
							<?php if ($answer['answer_image']) : ?>
								<picture class="mr-6">
								<source srcset="<?= $answer['answer_image']['sizes']['card'] ?>">
								<img class="" alt="<?= $answer['answer_image']['alt'] ?>">
							</picture>
							<?php endif; ?>

							<?php if ($answer['emoji_icon']) : ?>
								<span class="mr-6 text-35"><?= $answer['emoji_icon'] ?></span>						
							<?php endif; ?>
							
							<?php if($question['question_type'] == 'single'): ?>
								<button class="text-left leading-28 <?= $question['question_type'] ?>" data-question-type="<?= $question['question_type'] ?>" data-question="<?= $question_counter ?>" data-branch="<?= $answer['branch'] ?>" data-question-title="<?= $question['question_copy'] ?>" data-question-answer="<?= $answer['answer'] ?>" <?php if (!empty($results)) : ?> data-results="<?php echo implode(",", $results); ?>" <?php endif; ?>><?= $answer['answer'] ?></button>
							<?php else: ?>
								<span class="block leading-28 cursor-pointer <?= $question['question_type'] ?>"><?= $answer['answer'] ?></span>								
							<?php endif; ?>
						</li>
					<?php endforeach; ?>

				</ul>
				<div class="flex gap-3 justify-center relative mb-20 px-1 md:px-8">
					<div class="grow text-center">
						<button class="back-button grow w-full max-w-xs rounded-full px-12 py-4 font-title text-12 bg-black text-white uppercase">Back</button>
					</div>
					<?php if($show_next): ?>
					<div class="grow">
						<button class="next-button grow w-full rounded-full px-12 py-4 font-title text-12 bg-black text-white disabled:bg-gray disabled:text-black uppercase" disabled data-question="<?= $question_counter ?>">Next</button>
					</div>
					<?php endif; ?>
				</div>
			</div>
			<?php $question_counter++ ?>
		<?php endforeach; ?>

		<div class="quiz-card branch-final h-0 flex flex-col items-center content-center text-center max-w-lg mx-auto w-full" id="submit-card">
			<div class="max-w-sm mx-auto">
				<h2 class="font-title text-black text-24 leading-30 md:leading-44 text-center uppercase mb-8">What's your email?</h2>
				<p class="text-black text-center text-14 mb-8">Exclusive discounts, events and more await!</p>
				<input id="email" type="email" class="block mx-auto w-full max-w-md bg-white border border-black border-solid rounded-full px-12 py-4 mb-0" placeholder="Email" name="email" required>			
				
				<div id="form-messages" class="form-messages invisible h-0 text-red rounded-full my-2 p-2"></div>

				<div class="flex flex-col gap-3 justify-center w-full relative mb-20">
					<div class="grow">
						<button id="submit-quiz" class="submit grow w-full rounded-full px-12 py-4 font-title bg-black text-white hover:bg-accent-blue disabled:bg-gray disabled:text-black uppercase">Submit</button>
					</div>
					<div class="grow">
						<button class="back-button px-12 py-4 text-12 text-black hover:text-black underline uppercase">Back</button>
					</div>
				</div>
			</div>
		</div>

		<div id="results-card" class="quiz-card results h-0 max-w-3xl mx-auto mt-20 md:mt-24 rounded">
			<div class="flex flex-col gap-4">			
				<div class="results-stage"></div>						
			</div>
		</div>
	</div>					
</section>