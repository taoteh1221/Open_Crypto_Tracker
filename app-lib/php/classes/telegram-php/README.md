# Telegram-PHP

Another library to use Telegram bots with PHP.

- Include the **src/Autoloader.php** file.
- Create a *Telegram\Bot* object.
- Create a *Telegram\Receiver* object using the *$bot*.

```php
$bot = new Telegram\Bot("11111111:AAAAAAAAAAzzzzzzzzzzzzzzzzzzz", "MyUserBot", "The Name of Bot");
$tg  = new Telegram\Receiver($bot);
```

You can create as many *Bots* and *Receivers* or *Senders* as you want.
Using *Receiver* includes a *Sender*.

# Usage

Once the page is loaded (manually or via webhook), you can send or reply the requests.

To send a message to a user or group chat:
```php
$tg->send
  ->chat("123456")
  ->text("Hello world!")
->send();
```

To reply a user command:
```php
if($tg->text_command("start")){
  $tg->send
    ->text("Hi!")
  ->send();
}
```

To reply a user message:
```php
if($tg->text_has("are you alive")){
  $tg->send
    ->text("Yes!")
  ->send();
}
```

**NEW:** To parse a string:
```php
if($tg->text_regex("I'm {N:age}") and $tg->words() <= 4){
  $num = $tg->input->age;
  $str = "So old...";
  if($num < 18){ $str = "You're young!"; }
  $tg->send
    ->text($str)
  ->send();
}elseif($tg->text_regex("My name's {name}")){
  $tg->send
    ->text("Nice to meet you, " .$tg->input->name ."!")
  ->send();
}
```

Send an Inline Keyboard and parse it:
```php
if($tg->callback == "but 1"){
  $tg->answer_if_callback(""); // Stop loading button.
  $tg->send
    ->message(TRUE)
    ->chat(TRUE)
    ->text("You pressed the first button!")
  ->edit("text");
}elseif($tg->callback == "but 2"){
  $tg->answer_if_callback("You pressed the second button!", TRUE);
  // Display an alert and stop loading button.
}

if($tg->text_has("matrix") and $tg->words() <= 5){
  $tg->send
    ->text("Red or blue. You choose.")
    ->inline_keyboard()
      ->row()
        ->button("Red",  "but 1")
        ->button("Blue", "but 2")
      ->end_row()
    ->show()
  ->send();
}
```

# Examples
- [Profesor Oak](https://github.com/duhow/ProfesorOak), an assistant for Pokemon GO.
