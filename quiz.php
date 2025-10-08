<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Animated Quiz App</title>
    <style>
      body {
        font-family: "Poppins", sans-serif;
        background: linear-gradient(135deg, #39e2eeff, #982f8dff);
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0;
      }

      .quiz-box {
        background: white;
        padding: 30px;
        border-radius: 15px;
        max-width: 500px;
        width: 90%;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        text-align: center;
        animation: fadeIn 0.8s ease-in-out;
      }

      h2 {
        font-size: 1.3rem;
        color: #333;
      }

      #progress {
        font-weight: 600;
        margin-bottom: 10px;
      }

      .progress-bar {
        width: 100%;
        background: #ddd;
        border-radius: 10px;
        overflow: hidden;
        height: 10px;
        margin-bottom: 15px;
      }

      .progress-fill {
        width: 0%;
        height: 100%;
        background: linear-gradient(90deg, #2dabf9, #00e1ff);
        transition: width 0.5s ease;
      }

      .option {
        background: #f7f7f7;
        padding: 12px;
        margin: 8px 0;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
      }

      .option:hover {
        background: #e8f5ff;
        transform: scale(1.03);
      }

      .correct {
        background: #b2f5b2 !important;
        color: #046904;
        font-weight: bold;
      }

      .wrong {
        background: #ffb2b2 !important;
        color: #7a0000;
        font-weight: bold;
      }

      .button-container {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 20px;
      }

      button {
        padding: 10px 25px;
        border: none;
        border-radius: 25px;
        background: linear-gradient(to right, #2dabf9, #00e1ff);
        color: white;
        cursor: pointer;
        font-size: 1rem;
        transition: 0.3s;
      }

      button:hover {
        transform: scale(1.05);
        background: linear-gradient(to right, #00e1ff, #2dabf9);
      }

      #score {
        margin-top: 10px;
        font-weight: bold;
        color: #004aad;
      }

      @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
      }
    </style>
  </head>
  <body>

    <div class="quiz-box">
      <div id="progress">Loading...</div>
      <div class="progress-bar"><div id="progressFill" class="progress-fill"></div></div>
      <h2 id="question">Loading question...</h2>
      <div id="options"></div>
      <div id="score"></div>

      <div class="button-container">
        <button id="nextBtn" onclick="nextQuestion()">Next Question ➜</button>
        <button id="restartBtn" style="display:none;" onclick="restartQuiz()">🔁 Restart Quiz</button>
      </div>
    </div>

    <script>
      let quizData = [];
      let currentIndex = 0;
      let score = 0;

      async function loadQuizData() {
        let response = await fetch("https://opentdb.com/api.php?amount=10&category=17&difficulty=easy&type=multiple");
        let data = await response.json();
        quizData = data.results;
        currentIndex = 0;
        score = 0;
        showQuestion();
        updateProgress();
      }

      function showQuestion() {
        let quiz = quizData[currentIndex];
        document.getElementById("question").innerHTML = quiz.question;
        document.querySelector(".quiz-box").style.animation = "fadeIn 0.8s ease";

        let answers = [...quiz.incorrect_answers, quiz.correct_answer];
        answers.sort(() => Math.random() - 0.5);

        let optionsDiv = document.getElementById("options");
        optionsDiv.innerHTML = "";

        answers.forEach(answer => {
          let btn = document.createElement("div");
          btn.className = "option";
          btn.innerHTML = answer;
          btn.onclick = () => checkAnswer(btn, quiz.correct_answer);
          optionsDiv.appendChild(btn);
        });

        document.getElementById("score").innerHTML = `Score: ${score}`;
        updateProgress();
      }

      function checkAnswer(selected, correctAnswer) {
        let options = document.querySelectorAll(".option");
        options.forEach(opt => {
          if (opt.innerHTML === correctAnswer) {
            opt.classList.add("correct");
          } else if (opt === selected) {
            opt.classList.add("wrong");
          }
          opt.style.pointerEvents = "none";
        });

        if (selected.innerHTML === correctAnswer) {
          score++;
        }

        document.getElementById("score").innerHTML = `Score: ${score}`;
      }

      function nextQuestion(){
        if (currentIndex < quizData.length - 1){
          currentIndex++;
          showQuestion();
        } else {
          showResult();
        }
      }

      function showResult() {
        document.getElementById("question").innerHTML = `🎉 Quiz Completed!`;
        document.getElementById("options").innerHTML = `<h3>Your final score is ${score} / ${quizData.length}</h3>`;
        document.getElementById("nextBtn").style.display = "none";
        document.getElementById("restartBtn").style.display = "inline-block";
        document.getElementById("progress").innerHTML = "";
        document.getElementById("progressFill").style.width = "100%";
      }

      function restartQuiz() {
        document.getElementById("restartBtn").style.display = "none";
        document.getElementById("nextBtn").style.display = "inline-block";
        loadQuizData();
      }

      function updateProgress() {
        let progressText = `Question ${currentIndex + 1} of ${quizData.length}`;
        document.getElementById("progress").innerHTML = progressText;

        let progressPercent = ((currentIndex + 1) / quizData.length) * 100;
        document.getElementById("progressFill").style.width = `${progressPercent}%`;
      }

      loadQuizData();
    </script>
  </body>
</html>