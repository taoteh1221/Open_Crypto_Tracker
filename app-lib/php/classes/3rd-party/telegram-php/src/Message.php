<?php

// TODO
namespace Telegram;

class Message {
	public $message_id;
	public $from;
	public $user; // --
	public $date;
	public $chat;
	public $forward_from;
	public $forward_from_chat;
	public $forward_from_message_id;
	public $forward_signature;
	public $forward_date;
	public $reply_to_message;
	public $edit_date;
	public $author_signature;
	public $text;
	public $entities;
	public $caption_entities;
	public $audio;
	public $document;
	public $game;
	public $photo;
	public $sticker;
	public $video;
	public $voice;
	public $video_note;
	public $caption;
	public $contact;
	public $location;
	public $venue;
	public $new_chat_members;
	public $left_chat_member;
	public $new_chat_title;
	public $new_chat_photo;
	public $delete_chat_photo;
	public $group_chat_created;
	public $supergroup_chat_created;
	public $channel_chat_created;
	public $migrate_to_chat_id;
	public $migrate_from_chat_id;
	public $pinned_message;
	public $invoice;
	public $successful_payment;
}

?>
