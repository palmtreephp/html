<?php

namespace Palmtree\Html;

class Selector {
	protected $tag;
	protected $id;
	protected $classes = [];

	public static $pattern = '/(?<tags>[a-zA-Z]+)*(?<ids>#[^#\.]+)*(?<classes>(?:\.[^.#]+)*)/';

	public function __construct( $selector = null ) {
		if ( $selector !== null ) {
			$this->parse( $selector );
		}
	}

	public function parse( $selector ) {
		if ( mb_strpos( $selector, '#' ) === false && mb_strpos( $selector, '.' ) === false ) {
			$this->setTag( $selector );

			return;
		}

		$matches = [];
		preg_match_all( self::$pattern, $selector, $matches );

		if ( isset( $matches['tags'] ) ) {
			$tags = array_filter( $matches['tags'] );
			$tag  = end( $tags );
			$this->setTag( $tag );
		}

		if ( isset( $matches['ids'] ) ) {
			$ids = array_filter( $matches['ids'] );
			$id  = end( $ids );
			$this->setId( $id );
		}

		if ( isset( $matches['classes'] ) ) {
			$classes = array_filter( $matches['classes'] );

			foreach ( $classes as $class ) {
				$parts = explode( '.', trim( $class, '.' ) );

				foreach ( $parts as $part ) {
					$this->addClass( $part );
				}
			}
		}
	}

	/**
	 * @param mixed $tag
	 *
	 * @return Selector
	 */
	public function setTag( $tag ) {
		$this->tag = $tag;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getTag() {
		return $this->tag;
	}

	/**
	 * @param mixed $id
	 *
	 * @return Selector
	 */
	public function setId( $id ) {
		$this->id = $id;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return array
	 */
	public function getClasses() {
		return $this->classes;
	}

	/**
	 * @param array $classes
	 *
	 * @return Selector
	 */
	public function setClasses( $classes ) {
		$this->classes = $classes;

		return $this;
	}

	public function addClass( $class ) {
		$this->classes[] = $class;
	}
}
