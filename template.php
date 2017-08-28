<?php

function vakstars_pager_item_list($variables) {
  $items = $variables['items'];
  $extra_items = $variables['extra_items'];
  $title = $variables['title'];
  $type = $variables['type'];
  $attributes = $variables['attributes'];

  // Only output the list container and title, if there are any list items.
  // Check to see whether the block title exists before adding a header.
  // Empty headers are not semantic and present accessibility challenges.
  $output = '<nav class="pagination is-centered">';
  if (isset($title) && $title !== '') {
    $output .= '<h3>' . $title . '</h3>';
  }

  if (!empty($extra_items)) {
      foreach ($extra_items as $item) {
          $output .= $item;
      }
  }

  if (!empty($items)) {
    $output .= "<$type" . drupal_attributes($attributes) . '>';
    $num_items = count($items);
    $i = 0;
    foreach ($items as $item) {
      $attributes = array();
      $children = array();
      $data = '';
      $i++;
      if (is_array($item)) {
        foreach ($item as $key => $value) {
          if ($key == 'data') {
            $data = $value;
          }
          elseif ($key == 'children') {
            $children = $value;
          }
          else {
            $attributes[$key] = $value;
          }
        }
      }
      else {
        $data = $item;
      }
      if (count($children) > 0) {
        // Render nested list.
        $data .= theme_item_list(array('items' => $children, 'title' => NULL, 'type' => $type, 'attributes' => $attributes));
      }
      if ($i == 1) {
        $attributes['class'][] = 'first';
      }
      if ($i == $num_items) {
        $attributes['class'][] = 'last';
      }
      $output .= '<li' . drupal_attributes($attributes) . '>' . $data . "</li>\n";
    }
    $output .= "</$type>";
  }
  $output .= '</nav>';
  return $output;
}

/**
 * Returns HTML for the "first page" link in a query pager.
 *
 * @param $variables
 *   An associative array containing:
 *   - text: The name (or image) of the link.
 *   - element: An optional integer to distinguish between multiple pagers on
 *     one page.
 *   - parameters: An associative array of query string parameters to append to
 *     the pager links.
 *
 * @ingroup themeable
 */
function vakstars_pager_first($variables) {
  $text = $variables['text'];
  $element = $variables['element'];
  $parameters = $variables['parameters'];
  global $pager_page_array;
  $output = '';

  // If we are anywhere but the first page
  if ($pager_page_array[$element] > 0) {
    $output = theme('pager_link', array('text' => $text, 'page_new' => pager_load_array(0, $element, $pager_page_array), 'element' => $element, 'parameters' => $parameters, 'attributes' => array('class' => array('pagination-previous'))));
  }

  return $output;
}

/**
 * Returns HTML for the "previous page" link in a query pager.
 *
 * @param $variables
 *   An associative array containing:
 *   - text: The name (or image) of the link.
 *   - element: An optional integer to distinguish between multiple pagers on
 *     one page.
 *   - interval: The number of pages to move backward when the link is clicked.
 *   - parameters: An associative array of query string parameters to append to
 *     the pager links.
 *
 * @ingroup themeable
 */
function vakstars_pager_previous($variables) {
  $text = $variables['text'];
  $element = $variables['element'];
  $interval = $variables['interval'];
  $parameters = $variables['parameters'];
  global $pager_page_array;
  $output = '';

  // If we are anywhere but the first page
  if ($pager_page_array[$element] > 0) {
    $page_new = pager_load_array($pager_page_array[$element] - $interval, $element, $pager_page_array);

    // If the previous page is the first page, mark the link as such.
    if ($page_new[$element] == 0) {
      $output = theme('pager_first', array('text' => $text, 'element' => $element, 'parameters' => $parameters));
    }
    // The previous page is not the first page.
    else {
      $output = theme('pager_link', array('text' => $text, 'page_new' => $page_new, 'element' => $element, 'parameters' => $parameters, 'attributes' => array('class' => array('pagination-previous'))));
    }
  }

  return $output;
}

/**
 * Returns HTML for the "next page" link in a query pager.
 *
 * @param $variables
 *   An associative array containing:
 *   - text: The name (or image) of the link.
 *   - element: An optional integer to distinguish between multiple pagers on
 *     one page.
 *   - interval: The number of pages to move forward when the link is clicked.
 *   - parameters: An associative array of query string parameters to append to
 *     the pager links.
 *
 * @ingroup themeable
 */
function vakstars_pager_next($variables) {
  $text = $variables['text'];
  $element = $variables['element'];
  $interval = $variables['interval'];
  $parameters = $variables['parameters'];
  global $pager_page_array, $pager_total;
  $output = '';

  // If we are anywhere but the last page
  if ($pager_page_array[$element] < ($pager_total[$element] - 1)) {
    $page_new = pager_load_array($pager_page_array[$element] + $interval, $element, $pager_page_array);
    // If the next page is the last page, mark the link as such.
    if ($page_new[$element] == ($pager_total[$element] - 1)) {
      $output = theme('pager_last', array('text' => $text, 'element' => $element, 'parameters' => $parameters));
    }
    // The next page is not the last page.
    else {
      $output = theme('pager_link', array('text' => $text, 'page_new' => $page_new, 'element' => $element, 'parameters' => $parameters, 'attributes' => array('class' => array('pagination-next'))));
    }
  }

  return $output;
}

/**
 * Returns HTML for the "last page" link in query pager.
 *
 * @param $variables
 *   An associative array containing:
 *   - text: The name (or image) of the link.
 *   - element: An optional integer to distinguish between multiple pagers on
 *     one page.
 *   - parameters: An associative array of query string parameters to append to
 *     the pager links.
 *
 * @ingroup themeable
 */
function vakstars_pager_last($variables) {
  $text = $variables['text'];
  $element = $variables['element'];
  $parameters = $variables['parameters'];
  global $pager_page_array, $pager_total;
  $output = '';

  // If we are anywhere but the last page
  if ($pager_page_array[$element] < ($pager_total[$element] - 1)) {
    $output = theme('pager_link', array(
      'text' => $text,
      'page_new' => pager_load_array($pager_total[$element] - 1, $element, $pager_page_array),
      'element' => $element,
      'parameters' => $parameters,
      'attributes' => array('class' => array('pagination-next'))
    ));
  }

  return $output;
}


/**
 * Returns HTML for a link to a specific query result page.
 *
 * @param $variables
 *   An associative array containing:
 *   - text: The link text. Also used to figure out the title attribute of the
 *     link, if it is not provided in $variables['attributes']['title']; in
 *     this case, $variables['text'] must be one of the standard pager link
 *     text strings that would be generated by the pager theme functions, such
 *     as a number or t('« first').
 *   - page_new: The first result to display on the linked page.
 *   - element: An optional integer to distinguish between multiple pagers on
 *     one page.
 *   - parameters: An associative array of query string parameters to append to
 *     the pager link.
 *   - attributes: An associative array of HTML attributes to apply to the
 *     pager link.
 *
 * @see theme_pager()
 *
 * @ingroup themeable
 */
function vakstars_pager_link($variables) {
  $text = $variables['text'];
  $page_new = $variables['page_new'];
  $element = $variables['element'];
  $parameters = $variables['parameters'];
  $attributes = $variables['attributes'];
  $attributes['class'][] = "pagination-link";

  $page = isset($_GET['page']) ? $_GET['page'] : '';
  if ($new_page = implode(',', pager_load_array($page_new[$element], $element, explode(',', $page)))) {
    $parameters['page'] = $new_page;
  }

  $query = array();
  if (count($parameters)) {
    $query = drupal_get_query_parameters($parameters, array());
  }
  if ($query_pager = pager_get_query_parameters()) {
    $query = array_merge($query, $query_pager);
  }

  // Set each pager link title
  if (!isset($attributes['title'])) {
    static $titles = NULL;
    if (!isset($titles)) {
      $titles = array(
        t('« first') => t('Go to first page'),
        t('‹ previous') => t('Go to previous page'),
        t('next ›') => t('Go to next page'),
        t('last »') => t('Go to last page'),
      );
    }
    if (isset($titles[$text])) {
      $attributes['title'] = $titles[$text];
    }
    elseif (is_numeric($text)) {
      $attributes['title'] = t('Go to page @number', array('@number' => $text));
    }
  }

  // @todo l() cannot be used here, since it adds an 'active' class based on the
  //   path only (which is always the current path for pager links). Apparently,
  //   none of the pager links is active at any time - but it should still be
  //   possible to use l() here.
  // @see http://drupal.org/node/1410574
  $attributes['href'] = url($_GET['q'], array('query' => $query));
  return '<a' . drupal_attributes($attributes) . '>' . check_plain($text) . '</a>';
}



function vakstars_pager($variables) {

  $tags = $variables['tags'];
  $element = $variables['element'];
  $parameters = $variables['parameters'];
  $quantity = $variables['quantity'];
  global $pager_page_array, $pager_total;

  // Calculate various markers within this pager piece:
  // Middle is used to "center" pages around the current page.
  $pager_middle = ceil($quantity / 2);
  // current is the page we are currently paged to
  $pager_current = $pager_page_array[$element] + 1;
  // first is the first page listed by this pager piece (re quantity)
  $pager_first = $pager_current - $pager_middle + 1;
  // last is the last page listed by this pager piece (re quantity)
  $pager_last = $pager_current + $quantity - $pager_middle;
  // max is the maximum page number
  $pager_max = $pager_total[$element];
  // End of marker calculations.

  // Prepare for generation loop.
  $i = $pager_first;
  if ($pager_last > $pager_max) {
    // Adjust "center" if at end of query.
    $i = $i + ($pager_max - $pager_last);
    $pager_last = $pager_max;
  }
  if ($i <= 0) {
    // Adjust "center" if at start of query.
    $pager_last = $pager_last + (1 - $i);
    $i = 1;
  }
  // End of generation loop preparation.

  $pager_link_first = theme('pager_first', array('text' => (isset($tags[0]) ? $tags[0] : t('« first')), 'element' => $element, 'parameters' => $parameters));
  $pager_link_previous = theme('pager_previous', array('text' => (isset($tags[1]) ? $tags[1] : t('‹ previous')), 'element' => $element, 'interval' => 1, 'parameters' => $parameters));
  $pager_link_next = theme('pager_next', array('text' => (isset($tags[3]) ? $tags[3] : t('next ›')), 'element' => $element, 'interval' => 1, 'parameters' => $parameters));
  $pager_link_last = theme('pager_last', array('text' => (isset($tags[4]) ? $tags[4] : t('last »')), 'element' => $element, 'parameters' => $parameters));

  if ($pager_total[$element] > 1) {

    // When there is more than one page, create the pager list.
    if ($i != $pager_max) {
      if ($i > 1) {
        $items[] = array(
          'class' => array('pagination-ellipsis'),
          'data' => '…',
        );
      }
      // Now generate the actual pager piece.
      for (; $i <= $pager_last && $i <= $pager_max; $i++) {
        if ($i < $pager_current) {
          $items[] = array(
            'data' => theme('pager_previous', array('text' => $i, 'element' => $element, 'interval' => ($pager_current - $i), 'parameters' => $parameters)),
          );
        }
        if ($i == $pager_current) {
          $items[] = array(
            'data' => theme('pager_link', array('text' => $i, 'element' => $element, 'interval' => ($pager_current), 'parameters' => $parameters, 'attributes' => array('class' => array('is-current')))));
        }
        if ($i > $pager_current) {
          $items[] = array(
            'data' => theme('pager_next', array('text' => $i, 'element' => $element, 'interval' => ($i - $pager_current), 'parameters' => $parameters)),
          );
        }
      }
      if ($i < $pager_max) {
        $items[] = array(
          'class' => array('pagination-ellipsis'),
          'data' => '…',
        );
      }
    }
  //we wrap this in *gasp* so

    $pager_items = vakstars_pager_item_list(array(
        'type' => 'ul',
        'items' => $items,
        'title' => NULL,
        'attributes' => array('class' => array('pagination-list') ),
        'extra_items' => array($pager_link_first, $pager_link_previous, $pager_link_next, $pager_link_last)
    ));

    return '<h2 class="element-invisible">' . t('Pages') . '</h2>' . $pager_items;
  }
}

function vakstars_menu_tree__main_menu($variables){
    if (isset($variables['#tree']['#weight'])) { // This is just a bet, probably works well for deciding whether it's a root menu or not
        return '<nav class="navbar">
                    <div class="navbar-brand">
                        <a class="navbar-item" href="/">
                          <img class="vak-logo" src="' .base_path() . path_to_theme() . '/img/vaci-anime-klub-logo-uj-aranyok-500-opt.png" alt="Váci Anime Klub"/>
                        </a>
                        <div class="navbar-burger burger button is-primary" data-target="navMenu">
                            <span></span><span></span><span></span>
                        </div>
                    </div>
                    <div class="navbar-menu" id="navMenu"><div class="navbar-start">' .
                        $variables['tree'] .
                    '</div></div>
                </nav>';
    }
    else {
        return $variables['tree'];
    }
}

function vakstars_menu_link__main_menu($variables) {
    $element = $variables['element'];
    $options = $element['#localized_options'];

    if (isset($options['attributes']['class']) && in_array('active-trail', $options['attributes']['class'])) {
        $options['attributes']['class'][] = 'is-active';
    }

    $output = '';
    if ($element['#below']) {
        $options['attributes']['class'][] = 'navbar-link';
        $sub_menu = drupal_render($element['#below']);
        $output .=
            '<div class="navbar-item has-dropdown is-hoverable">' .
            l($element['#title'], $element['#href'], $options) .
            '<div class="navbar-dropdown">' . $sub_menu . '</div>' .
            '</div>';
    }
    else {
        $options['attributes']['class'][] = 'navbar-item';
        $output .= l($element['#title'], $element['#href'], $options);
    }

    return $output . "\n";
}

function vakstars_status_messages($variables) {
    $display = $variables['display'];
    $output = '';

    $status_heading = array(
        'status' => t('Status message'),
        'error' => t('Error message'),
        'warning' => t('Warning message'),
    );
    $type_to_class = array(
        'status' => 'is-success',
        'error' => 'is-danger',
        'warning' => 'is-warning',
    );
    foreach (drupal_get_messages($display) as $type => $messages) {
        $output .= "<div class=\"messages notification $type_to_class[$type] \">\n";
        if (!empty($status_heading[$type])) {
            $output .= '<h2 class="element-invisible">' . $status_heading[$type] . "</h2>\n";
        }
        if (count($messages) > 1) {
            $output .= " <ul>\n";
            foreach ($messages as $message) {
                $output .= '  <li>' . $message . "</li>\n";
            }
            $output .= " </ul>\n";
        }
        else {
            $output .= reset($messages);
        }
        $output .= "</div>\n";
    }
    return $output;
}

function vakstars_form_comment_form_alter(&$form, &$form_state, $form_id) {
    // This is most likely a Drupal bug
    $form['comment_body'][LANGUAGE_NONE][0]['#title'] = t('Comment');
}

function vakstars_menu_local_tasks(&$variables) {
    $output = '';

    if ( ! empty($variables['primary'])) {
        $variables['primary']['#prefix'] = '<h2 class="element-invisible">'.t('Primary tabs').'</h2>';
        $variables['primary']['#prefix'] .= '<ul class="primary">';
        $variables['primary']['#suffix'] = '</ul>';
        $output .= drupal_render($variables['primary']);
    }
    if ( ! empty($variables['secondary'])) {
        $variables['secondary']['#prefix'] = '<h2 class="element-invisible">'.t('Secondary tabs').'</h2>';
        $variables['secondary']['#prefix'] .= '<ul class="secondary">';
        $variables['secondary']['#suffix'] = '</ul>';
        $output .= drupal_render($variables['secondary']);
    }
    return $output;
}

function vakstars_menu_local_task($variables) {
    $link = $variables['element']['#link'];
    $link_text = $link['title'];

    if (!empty($variables['element']['#active'])) {
        // Add text to indicate active tab for non-visual users.
        $active = '<span class="element-invisible">' . t('(active tab)') . '</span>';

        // If the link does not contain HTML already, check_plain() it now.
        // After we set 'html'=TRUE the link will not be sanitized by l().
        if (empty($link['localized_options']['html'])) {
            $link['title'] = check_plain($link['title']);
        }
        $link['localized_options']['html'] = TRUE;
        $link_text = t('!local-task-title!active', array('!local-task-title' => $link['title'], '!active' => $active));
    }

    return '<li' . (!empty($variables['element']['#active']) ? ' class="is-active"' : '') . '>' . l($link_text, $link['href'], $link['localized_options']) . "</li>\n";
}

function vakstars_form_element($variables) {

    $element = &$variables['element'];
    // This is also used in the installer, pre-database setup.
    $t = get_t();

    // This function is invoked as theme wrapper, but the rendered form element
    // may not necessarily have been processed by form_builder().
    $element += array(
        '#title_display' => 'before',
    );

    // Add element #id for #type 'item'.
    if (isset($element['#markup']) && !empty($element['#id'])) {
        $attributes['id'] = $element['#id'];
    }
    // Add element's #type and #name as class to aid with JS/CSS selectors.

    $attributes['class'] = array();
    $attributes['class'][] = 'control';
    if(! theme_get_setting('mothership_classes_form_wrapper_formitem')){
        $attributes['class'] = array('form-item');
    }

    //date selects need the form-item for the show/hide end date
    if(isset($element['#type'])){
        if ($element['#type'] == 'date_select' OR $element['#type'] == 'date_text' OR $element['#type'] == 'date_popup' ){ //AND
            $attributes['class'] = array('form-item');
        }
    }

    if (!empty($element['#type'])) {
        if(!theme_get_setting('mothership_classes_form_wrapper_formtype')){
            $attributes['class'][] = 'form-type-' . strtr($element['#type'], '_', '-');
        }

        switch ($element['#type']) {
            case 'checkbox': $element['#attributes']['class'][] = 'checkbox'; break;
            case 'textfield':
            case 'password':
                $element['#attributes']['class'][] = 'input';
                break;
            case 'textarea':
                $element['#attributes']['class'][] = 'textarea';
                break;
        }
        // Some extra stuff for the person selector
        if ($element['#type'] == 'checkbox' && $element['#parents'][0] == 'field_recipient') {
            $attributes['class'][] = 'box';
        }
    }
    if (!empty($element['#name'])) {
        if(!theme_get_setting('mothership_classes_form_wrapper_formname')){
            $attributes['class'][] = 'form-item-' . strtr($element['#name'], array(' ' => '-', '_' => '-', '[' => '-', ']' => ''));
        }
    }
    // Add a class for disabled elements to facilitate cross-browser styling.
    if (!empty($element['#attributes']['disabled'])) {
        $attributes['class'][] = 'form-disabled';
    }

    if(isset($element['#title']) && $element['#title'] != 'Language' && $element['#required']) {
        $attributes['class'][] = 'form-required';
    }


    //freeform css class killing \m/
    if($attributes['class']){
        $remove_class_form = explode(", ", theme_get_setting('mothership_classes_form_freeform'));
        $attributes['class'] = array_values(array_diff($attributes['class'],$remove_class_form));
    }

    if($attributes['class']){
        $output =  '<div' . drupal_attributes($attributes) . '>' . "\n";
    }else{
        $output =  "\n" . '<div>' . "\n";
    }


    // If #title is not set, we don't display any label or required marker.
    if (!isset($element['#title'])) {
        $element['#title_display'] = 'none';
    }
    $prefix = isset($element['#field_prefix']) ? '<span class="field-prefix">' . $element['#field_prefix'] . '</span> ' : '';
    $suffix = isset($element['#field_suffix']) ? ' <span class="field-suffix">' . $element['#field_suffix'] . '</span>' : '';

    switch ($element['#title_display']) {
        case 'before':
        case 'invisible':
            $output .= ' ' . theme('form_element_label', $variables);
            $output .= ' ' . $prefix . $element['#children'] . $suffix . "\n";
            break;

        case 'after':
            $output .= ' ' . $prefix . $element['#children'] . $suffix;
            $output .= ' ' . theme('form_element_label', $variables) . "\n";
            break;

        case 'none':
        case 'attribute':
            // Output no label and no required marker, only the children.
            $output .= ' ' . $prefix . $element['#children'] . $suffix . "\n";
            break;
    }

    if (!empty($element['#description'])) {

        /*
        changes the description <div class="description"> to <small>
        */
        if(!theme_get_setting('mothership_classes_form_description')){
            $output .= "\n" . '<div class="description">' . $element['#description'] . "</div>\n";
        }else{
            $output .= "\n" . '<small>' . $element['#description'] . "</small>\n";
        }


    }

    $output .= "</div>\n";

    return $output;
}

function vakstars_form_element_label($variables) {
    $element = $variables['element'];

    // This is also used in the installer, pre-database setup.
    $t = get_t();

    // If title and required marker are both empty, output no label.
    if (empty($element['#title']) && empty($element['#required'])) {
        return '';
    }

    $attributes = array();
    $attributes['class'][] = 'label';

    // If the element is required, a required marker is appended to the label.
    // We dont cause we belive in the power of css and less crap in the markup so we add it in a class instead.
    if(!theme_get_setting('mothership_form_required')){
        $required = !empty($element['#required']) ? theme('form_required_marker', array('element' => $element)) : '';
    }else{
        if(!empty($element['#required'])){
//       $attributes['class'] = 'form-field-required';
            $attributes['class'][] = 'required';
        }
    }

    $title = filter_xss_admin($element['#title']);

    // Style the label as class option to display inline with the element.
    if ($element['#title_display'] == 'after') {
        if(!theme_get_setting('mothership_classes_form_label')){
            $attributes['class'][] = 'option';
        }
    }
    // Show label only to screen readers to avoid disruption in visual flows.
    elseif ($element['#title_display'] == 'invisible') {
        $attributes['class'][] = 'element-invisible';
    }

    //FOR attribute
    // in html5 we need an element for the for id items & check TODO: clean this up
    if (!empty($element['#id'])){
        // not every element in drupal comes with an #id that we can use for the for="#id"
        // AND
        if(
            //if its html5 & is not an item, checkboxradios or manged file
            theme_get_setting('mothership_html5') AND
            $element['#type'] != "item" &&
            $element['#type'] != "checkboxes" &&
            $element['#type'] != "radios" &&
            $element['#type'] != "managed_file")
        {
            $attributes['for'] = $element['#id'];
        }else{
            $attributes['for'] = $element['#id'];
        }
    }

    // The leading whitespace helps visually separate fields from inline labels.
    if($attributes){
        if(!theme_get_setting('mothership_form_required')){
            return ' <label' . drupal_attributes($attributes) . '>' . $t('!title !required', array('!title' => $title, '!required' => $required)) . "</label>\n";
        }else{
            return ' <label' . drupal_attributes($attributes) . '>' . $t('!title', array('!title' => $title )) . "</label>\n";
        }
    }else{
        if(!theme_get_setting('mothership_form_required')){
            return ' <label>' . $t('!title !required', array('!title' => $title, '!required' => $required)) . "</label>\n";
        }else{
            return ' <label>' . $t('!title', array('!title' => $title )) . "</label>\n";
        }
    }

}

function vakstars_textfield($variables) {
    $element = $variables['element'];
    $element['#size'] = '30';

    //is this element requred then lest add the required element into the input
    $required = !empty($element['#required']) ? ' required' : '';

    //dont need to set type in html5 its default so lets remove it because we can
    $element['#attributes']['type'] = 'text';

    //placeholder
    if (!empty($element['#title']) AND theme_get_setting('mothership_classes_form_placeholder_label') ) {
        $element['#attributes']['placeholder'] =  $element['#title'];
    }


    element_set_attributes($element, array('id', 'name', 'value', 'size', 'maxlength'));

    //remove the form-text class
    if(!theme_get_setting('mothership_classes_form_input')){
        _form_set_class($element, array('form-text'));
    }
    $extra = '';
    if ($element['#autocomplete_path'] && drupal_valid_path($element['#autocomplete_path'])) {
        drupal_add_library('system', 'drupal.autocomplete');
        $element['#attributes']['class'][] = 'form-autocomplete';

        $attributes = array();
        $attributes['type'] = 'hidden';
        $attributes['id'] = $element['#attributes']['id'] . '-autocomplete';
        $attributes['value'] = url($element['#autocomplete_path'], array('absolute' => TRUE));
        $attributes['disabled'] = 'disabled';
        $attributes['class'][] = 'autocomplete';
        $extra = '<input' . drupal_attributes($attributes) . $required .' />';
    }

    $element['#attributes']['class'][] = 'input';
    $output = '<input' . drupal_attributes($element['#attributes']) . $required . ' />';

    return $output . $extra;
}

function vakstars_textarea($variables) {
    $element = $variables['element'];
    element_set_attributes($element, array('id', 'name', 'cols', 'rows'));
    if(!theme_get_setting('mothership_classes_form_input')){
        _form_set_class($element, array('form-textarea'));
    }

    $wrapper_attributes = array(
        'class' => array('form-textarea-wrapper'),
    );



    if (!empty($element['#title'])  AND theme_get_setting('mothership_classes_form_placeholder_label') ) {
        $element['#attributes']['placeholder'] = $element['#title'];
    }

    // Add resizable behavior.
    if (!empty($element['#resizable'])) {
        drupal_add_library('system', 'drupal.textarea');
        $wrapper_attributes['class'][] = 'resizable';
    }

    //is this element requred then lest add the required element into the input
    $required = !empty($element['#required']) ? ' required' : '';

    $element['#attributes']['class'][] = 'textarea';

    $output = '<div' . drupal_attributes($wrapper_attributes) . '>';
    $output .= '<textarea' . drupal_attributes($element['#attributes']) . $required .'>' . check_plain($element['#value']) . '</textarea>';
    $output .= '</div>';
    return $output;
}

function vakstars_checkbox($variables) {
    $element = $variables['element'];
    $t = get_t();
    $element['#attributes']['type'] = 'checkbox';
    element_set_attributes($element, array('id', 'name', '#return_value' => 'value'));

    // Unchecked checkbox has #value of integer 0.
    if (!empty($element['#checked'])) {
        $element['#attributes']['checked'] = 'checked';
    }
    if(!theme_get_setting('mothership_classes_form_input')){
        _form_set_class($element, array('form-checkbox'));
    }
    $element['#attributes']['class'][] = 'checkbox';

    return '<input' . drupal_attributes($element['#attributes']) . ' />';
}

function vakstars_password($variables) {
    $element = $variables['element'];
    $element['#size'] = '30';
    $element['#attributes']['type'] = 'password';

    element_set_attributes($element, array('id', 'name', 'size', 'maxlength'));
//  element_set_attributes($element, array('id', 'name',  'maxlength'));
    if(!theme_get_setting('mothership_classes_form_input')){
        _form_set_class($element, array('form-text'));
    }

    //html5 plceholder love ? //substr(,0, 20);
    if (!empty($element['#description']) AND theme_get_setting('mothership_classes_form_placeholder_description') ) {
        $element['#attributes']['placeholder'] = $element['#description'];
    }

    if (!empty($element['#title']) AND theme_get_setting('mothership_classes_form_placeholder_label')) {
        $element['#attributes']['placeholder'] = $element['#title'];
    }


    $element['#attributes']['class'][] = 'input';

    if($variables['element']['#id'] == "edit-pass-pass1"){
        return '<input' . drupal_attributes($element['#attributes']) . ' /><small>'. t('Enter a password').'</small>' ;
    }elseif($variables['element']['#id'] == "edit-pass-pass2"){
        return '<input' . drupal_attributes($element['#attributes']) . ' /><small>'. t('Repeat the password').'</small>' ;
    }else{
        return '<input' . drupal_attributes($element['#attributes']) . ' />' ;
    }

}