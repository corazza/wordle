<?php

class GameController extends BaseController
{
  private function showGameFor($name) {
    $ws = new WordleService();
    $context = $ws->getContextFor($name);

    echo "context: ";
    print_r($context);
    echo "<br/>";

    $this->registry->template->title = 'Wordle';
    $this->registry->template->name = $context->name;
    $this->registry->template->won = $context->won;
    $this->registry->template->length = $context->length;
    $this->registry->template->attempts = $context->attempts;
    $this->registry->template->sup_attempts = $context->sup_attempts;
    $this->registry->template->hints = $context->hints;
    $this->registry->template->big_hints = $context->big_hints;
    $this->registry->template->green_color = getGreenColorIndex($context);
    $this->registry->template->brown_color = getBrownColorIndex($context);

    if ($context->won) {
      $this->registry->template->show('won_index');
    } else {
      $this->registry->template->show('game_index');
    }
  }

  public function index()
  {
    $request_has_name = isset($_POST["name"]);
    $session_has_name = isset($_SESSION["name"]);

    $ws = new WordleService();
  
    if ($request_has_name) {
      $name = $_POST["name"];
      $_SESSION["name"] = $name;
      if (!$ws->hasContextFor($name)) {
        $length = $_POST["length"];
        $ws->createNewContext($name, $length);
      }
    } elseif ($session_has_name) {
      $name = $_SESSION["name"];
      if (!$ws->hasContextFor($name)) {
        exit("logic error (GameController.index 1)");
      }
    } else {
      exit("logic error (GameController.index 2)");
    }

    $this->showGameFor($name);
  }

  public function action() {
    if (strcmp($_POST["akcija"], "pogodi") === 0) {
      $this->attempt();
    } elseif (strcmp($_POST["akcija"], "hint") === 0) {
      $this->hint();
    } elseif (strcmp($_POST["akcija"], "veliki_hint") === 0) {
      $this->velikiHint();
    } else {
      echo "nepoznata akcija";
    }
  }

  private function hint() {
    $name = $_SESSION["name"];
    $ws = new WordleService();
    $ws->hintFor($name);
    $this->showGameFor($name);
  }

  private function velikiHint() {
    $name = $_SESSION["name"];
    $ws = new WordleService();
    $ws->bigHintFor($name);
    $this->showGameFor($name);
  }

  private function attempt() {
    $name = $_SESSION["name"];
    $attempt = $_POST["attempt"];
    $ws = new WordleService();
    $context = $ws->getContextFor($name);
    if (intval(strlen($attempt)) === intval($context->length)) {
      $ws->makeAttempt($name, $attempt);
    } else {
      $ws->addSupAttempt($name);
    }
    $this->showGameFor($name);
  }

  public function newgame() {
    $name = $_SESSION["name"];
    $ws = new WordleService();
    $ws->resetContext($name);
    header('Location: ' . __SITE_URL . '/wordle.php?rt=start');
    exit;
  }
};
