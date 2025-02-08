
/**
 * External dependencies
 */
import { isUndefined, pickBy } from 'lodash';
import classnames from 'classnames';

/**
 * WordPress dependencies
 */
import { Component, RawHTML } from '@wordpress/element';
import {
	PanelBody,
	Placeholder,
	QueryControls,
	RadioControl,
	RangeControl,
	SelectControl,
	Spinner,
	ToggleControl,
	ToolbarGroup,
} from '@wordpress/components';
import apiFetch from '@wordpress/api-fetch';
import { addQueryArgs } from '@wordpress/url';
import { __ } from '@wordpress/i18n';
import { dateI18n, format, __experimentalGetSettings } from '@wordpress/date';
import { InspectorControls, BlockControls } from '@wordpress/block-editor';
import { withSelect } from '@wordpress/data';

/**
 * Module Constants
 */
const CATEGORIES_LIST_QUERY = {
	per_page: -1,
};
const MAX_POSTS_COLUMNS = 6;

class LatestPostsEdit extends Component {
	constructor() {
		super( ...arguments );
		this.state = {
			categoriesList: [],
		};
	}

	componentDidMount() {
		this.isStillMounted = true;
		this.fetchRequest = apiFetch( {
			path: addQueryArgs( `/wp/v2/categories`, CATEGORIES_LIST_QUERY ),
		} )
			.then( ( categoriesList ) => {
				if ( this.isStillMounted ) {
					this.setState( { categoriesList } );
				}
			} )
			.catch( () => {
				if ( this.isStillMounted ) {
					this.setState( { categoriesList: [] } );
				}
			} );
	}

	componentWillUnmount() {
		this.isStillMounted = false;
	}

	render() {
		const { attributes, setAttributes, latestPosts } = this.props;
		const { categoriesList } = this.state;
		const {
			displayPostContentRadio,
			displayPostContent,
			displayFeaturedImage,
			displayPostDate,
			postLayout,
			columns,
			order,
			orderBy,
			categories,
			postsToShow,
			postType,
			excerptLength,
		} = attributes;
		 const postTypesList =  [
			{
				"name": "Posts",
				"Slug": "post",
			},
			{
				"name": "Pages",
				"slug": "page",	
			},
	
		];
	  
		const inspectorControls =  (
			
			<InspectorControls>
				<PanelBody title={ __( 'Post type settings' ) }>
	`				<SelectControl
						label="Post Type"
						value={ postType  }
						onChange={ ( value ) => setAttributes( { postType: value } ) }
						>
						{postTypesList.map( (row, rowIndex) => (
                
						<option value={row.slug}>{ row.name}</option>
						))}

					</SelectControl>
				</PanelBody>

				<PanelBody title={ __( 'Post content settings' ) }>
					<ToggleControl
						label={ __( 'Post Content' ) }
						checked={ displayPostContent }
						onChange={ ( value ) => setAttributes( { displayPostContent: value } ) }
					/>	
					{ displayPostContent && (
						<RadioControl
							label={ __( 'Show:' ) }
							selected={ displayPostContentRadio }
							options={ [
								{ label: __( 'Excerpt' ), value: 'excerpt' },
								{ label: __( 'Full Post' ), value: 'full_post' },
							] }
							onChange={ ( value ) => setAttributes( { displayPostContentRadio: value } ) }
						/>
					) }
					{ displayPostContent && displayPostContentRadio === 'excerpt' && (
						<RangeControl
							label={ __( 'Max number of words in excerpt' ) }
							value={ excerptLength }
							onChange={ ( value ) => setAttributes( { excerptLength: value } ) }
							min={ 10 }
							max={ 100 }
						/>
					) }
				</PanelBody>

				<PanelBody title={ __( 'Featured Image settings' ) }>
					<ToggleControl
						label={ __( 'Featured Image' ) }
						checked={ displayFeaturedImage }
						onChange={ ( value ) => setAttributes( { displayFeaturedImage: value } ) }
					/>
				</PanelBody>
				
				<PanelBody title={ __( 'Post meta settings' ) }>
					<ToggleControl
						label={ __( 'Display post date' ) }
						checked={ displayPostDate }
						onChange={ ( value ) => setAttributes( { displayPostDate: value } ) }
					/>
				</PanelBody>

				<PanelBody title={ __( 'Sorting and filtering' ) }>
					<QueryControls
						{ ...{ order, orderBy } }
						numberOfItems={ postsToShow }
						onOrderChange={ ( value ) => setAttributes( { order: value } ) }
						onOrderByChange={ ( value ) => setAttributes( { orderBy: value } ) }
						onNumberOfItemsChange={ ( value ) => setAttributes( { postsToShow: value } ) }
					/>
					{ postLayout === 'grid' && (
						<RangeControl
							label={ __( 'Columns' ) }
							value={ columns }
							onChange={ ( value ) => setAttributes( { columns: value } ) }
							min={ 2 }
							max={
								! hasPosts ? MAX_POSTS_COLUMNS : Math.min( MAX_POSTS_COLUMNS, latestPosts.length )
							}
							required
						/>
					) }
				</PanelBody>
			</InspectorControls>
		);

		const hasPosts = Array.isArray( latestPosts ) && latestPosts.length;
		if ( ! hasPosts ) {
			return (
				<>
					{ inspectorControls }
					<Placeholder icon="admin-post" label={ __( 'Latest Posts' ) }>
						{ ! Array.isArray( latestPosts ) ? <Spinner /> : __( 'No posts found.' ) }
					</Placeholder>
				</>
			);
		}

		// Removing posts from display should be instant.
		const displayPosts =
			latestPosts.length > postsToShow ? latestPosts.slice( 0, postsToShow ) : latestPosts;

		const layoutControls = [
			{
				icon: 'list-view',
				title: __( 'List view' ),
				onClick: () => setAttributes( { postLayout: 'list' } ),
				isActive: postLayout === 'list',
			},
			{
				icon: 'grid-view',
				title: __( 'Grid view' ),
				onClick: () => setAttributes( { postLayout: 'grid' } ),
				isActive: postLayout === 'grid',
			},
		];

		const dateFormat = __experimentalGetSettings().formats.date;

		return (
			<>
				{ inspectorControls }
				<BlockControls>
					<ToolbarGroup controls={ layoutControls } />
				</BlockControls>
				<ul
					className={ classnames( this.props.className, {
						'em-block-latest-posts__list': true,
						'is-grid': postLayout === 'grid',
						'has-dates': displayPostDate,
						[ `columns-${ columns }` ]: postLayout === 'grid',
					} ) }
				>
					{ displayPosts.map( ( post, i ) => {
						if(post.title !== undefined){
							var titleTrimmed = post.title.rendered.trim();
						}
						else{
							var titleTrimmed = "No Title";
						}

                        if(post.excerpt !== undefined ){
							var excerpt = post.excerpt.rendered;
						}
						else{
							var excerpt = 'No Excerpt';
						}
			            if( post.excerpt  !== undefined){
							if ( post.excerpt.raw === '' ) {
								excerpt = post.content.raw;
							}
						}
						else{
							var excerpt = 'No Excerpt';
						}
						
						const excerptElement = document.createElement( 'div' );
						excerptElement.innerHTML = excerpt;
						excerpt = excerptElement.textContent || excerptElement.innerText || '';
						return (
							<li key={ i }>
								{ displayFeaturedImage && (
									<img class="em-block-featured-img" src={ post.fimg_url }/>
								) }								
								
								<a href={ post.link } target="_blank" rel="noreferrer noopener">
									{ titleTrimmed ? <RawHTML>{ titleTrimmed }</RawHTML> : __( '(no title)' ) }
								</a>
								{ displayPostDate && post.date_gmt && (
									<time
										dateTime={ format( 'c', post.date_gmt ) }
										className="em-block-latest-posts__post-date"
									>
										{ dateI18n( dateFormat, post.date_gmt ) }
									</time>
								) }
								{ displayPostContent && displayPostContentRadio === 'excerpt' && (
									<div className="em-block-latest-posts__post-excerpt">
										<RawHTML key="html">
											{ excerptLength < excerpt.trim().split( ' ' ).length
												? excerpt
														.trim()
														.split( ' ', excerptLength )
														.join( ' ' ) +
												  ' ... <a href=' +
												  post.link +
												  'target="_blank" rel="noopener noreferrer">' +
												  __( 'Read more' ) +
												  '</a>'
												: excerpt
														.trim()
														.split( ' ', excerptLength )
														.join( ' ' ) }
										</RawHTML>
									</div>
								) }
								{ displayPostContent && displayPostContentRadio === 'full_post' && (
									<div className="em-block-latest-posts__post-full-content">
										<RawHTML key="html">{ post.content.raw.trim() }</RawHTML>
									</div>
								) }
							</li>
						);
					} ) }
				</ul>
			</>
		);
	}
}

export default withSelect( ( select, props ) => {
	const { postsToShow, order, orderBy, categories , postType} = props.attributes;
	const { getEntityRecords } = select( 'core' );
	const latestPostsQuery = pickBy(
		{
			categories,
			order,
			orderby: orderBy,
			per_page: postsToShow,
			post_type: postType,
		},
		( value ) => ! isUndefined( value )
	);
	return {
		latestPosts: getEntityRecords( 'postType', postType, latestPostsQuery ),
	};
} )( LatestPostsEdit );