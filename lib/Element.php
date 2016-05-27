<?php

namespace Queue\Lib;

/**
 * Class Element
 * @package Queue\Lib
 */
class Element {

	/**
	 * @var
	 */
	private $queue;

	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var
	 */
	private $type;

	/**
	 * @var string
	 */
	private $status;

	/**
	 * @var null
	 */
	private $data;

	/**
	 * @var int
	 */
	private $priority;

	/**
	 * @var $date_added \DateTime
	 */
	private $date_added;

	/**
	 * @var $date_last_updated \DateTime
	 */
	private $date_last_updated;

	/**
	 * Serialized Data
	 * Contains: User info, dates
	 * @var $raw_data
	 */
	private $raw_data;

	/**
	 * @param $queue Queue
	 * @param $type
	 * @param $data
	 * @param $status Status
	 * @param $priority
	 *
	 * @return $this
	 */
	public function create( Queue $queue, $type, $data, Status $status = 'PENDING', $priority = 0 ) {
		//Set properties
		$this->queue             = $queue;
		$this->type              = $type;
		$this->data              = $data;
		$this->status            = $status;
		$this->priority          = $priority;
		$this->date_added        = new \DateTime( 'now' );
		$this->date_last_updated = new \DateTime( 'now' );
		$raw_data                = [
			'browser_data' => get_browser(),
			'request_ip'   => Helper::get_request_ip(),
		];
		$this->raw_data          = serialize( json_encode( $raw_data ) );

		//Todo: Log request and serialized data
		//Todo: Insert data into DB (This could vary depending on the Queue type - Persistent or not. We will assume everything is persistent at the moment)

		return $this;
	}

	/**
	 *
	 */
	public function update() {
	}

	/**
	 *
	 */
	public function disable() {
	}

	/**
	 *
	 */
	public function delete() {
	}

	/**
	 * @return mixed
	 */
	public function getQueue() {
		return $this->queue;
	}

	/**
	 * @param mixed $queue
	 *
	 * @return Element
	 */
	public function setQueue( $queue ) {
		$this->queue = $queue;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param int $id
	 *
	 * @return Element
	 */
	public function setId( $id ) {
		$this->id = $id;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @param mixed $type
	 *
	 * @return Element
	 */
	public function setType( $type ) {
		$this->type = $type;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * @param string $status
	 *
	 * @return Element
	 */
	public function setStatus( $status ) {
		$this->status = $status;

		return $this;
	}

	/**
	 * @return null
	 */
	public function getData() {
		return $this->data;
	}

	/**
	 * @param null $data
	 *
	 * @return Element
	 */
	public function setData( $data ) {
		$this->data = $data;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getPriority() {
		return $this->priority;
	}

	/**
	 * @param int $priority
	 *
	 * @return Element
	 */
	public function setPriority( $priority ) {
		$this->priority = $priority;

		return $this;
	}

	/**
	 * @return \DateTime
	 */
	public function getDateAdded() {
		return $this->date_added;
	}

	/**
	 * @param \DateTime $date_added
	 *
	 * @return Element
	 */
	public function setDateAdded( $date_added ) {
		$this->date_added = $date_added;

		return $this;
	}

	/**
	 * @return \DateTime
	 */
	public function getDateLastUpdated() {
		return $this->date_last_updated;
	}

	/**
	 * @param \DateTime $date_last_updated
	 *
	 * @return Element
	 */
	public function setDateLastUpdated( $date_last_updated ) {
		$this->date_last_updated = $date_last_updated;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getRawData() {
		return $this->raw_data;
	}

	/**
	 * @param mixed $raw_data
	 *
	 * @return Element
	 */
	public function setRawData( $raw_data ) {
		$this->raw_data = $raw_data;

		return $this;
	}


}