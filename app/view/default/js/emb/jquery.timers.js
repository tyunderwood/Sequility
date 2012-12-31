/**
* Author: Conan C. Albrecht <ca@byu.edu>
* Version: 0.5 (July 2012)
* Email any bugs or additions directly to me.
* 
* A simple plugin that provides the following types of timers:
*
*   1. A single-run timer (a.k.a. setTimeout)
*   2. A repeated-run timer (a.k.a. setInterval)
*   3. A repeated-run timer that runs immediately, then waits the interval.
*   4. A repeated-run, definite interval timer (see sleep option below).
*   5. A repeated-run, definite interval timer that runs immediately, then waits the sleep time.
*
*
* Features:
*
*   - Automatic timer id tracking.
*   - Five types of timers (all right, 3 plus 2 variants :).
*   - Pausing and later continuing of timers.
*   - Unique timers, meaning if you start a timer twice, the first one is
*     automatically cleared.
*   - Automatic timer removal when related elements are removed from the DOM.
*   - Multiple, named timers per element.
*   - The "this" variable is available in your timed function.
*   - Simply return false from a timer function to cancel the timer.
*   - A JQuery-like interface for more clear code.
*   - Allows parameter passing in both all browsers, including IE which normally doesn't pass parameters nicely
*
*
* A few things to keep in mind when using timers and this plugin:
*
*   1. If the given JQuery array has multiple elements in it, a timer will be set
*      on *each* element.  In other words, if your array contains two <div> elements,
*      you'll get two timers running, one for each div.  You probably want
*      to use a JQuery array with a single element, such as those given by id-based
*      selectors: $('#id').startTimer(), so you get a single timer.
*
*   2. If you want to send parameters to your function, use a JQuery-style params
*      object {}.  In other words, your function should be defined like this:
* 
*        function myFunc(params) { ... }
*           -or-
*        function myFunc() { ... }
*
*   3. If you remove DOM elements that have running timers attached to them,
*      the plugin will clear the timers automatically. 
*
*   4. If you want a page-wide timer that isn't connected to a specific element,
*      use the document object since it is stable and singular:
*
*        $(document).startTimer({...});
*
*   5. The "this" variable is set to the related DOM element during your function call, 
*      the same way JQuery events work.  Calling $(this) within your function will
*      refer to the DOM element you originally used when setting the timer.
*
*   6. Normally you only need one timer attached to an element, so there's no reason
*      to name the timer (it just uses the default name).  If you need additional timers
*      on an element, you may want to name them so you can stop/pause/continue the
*      timers individually. 
*
*   7. pauseTimer() and continueTimer() are really useful when doing asynchronous
*      Ajax calls.  Pause your timer when the ajax call is made, then continue it
*      when the response comes back.  IMHO, this is a more clear pattern than a recursive
*      setTimeout algorithm. 
*
*
* See the options comments at the start of the code for detail on the available options.
*
*
* Example 1: Sets a timer to run in 2 seconds:
*
*   $('#someid').startTimer({
*      delay: 2000,
*      params: {
*        first: 1,
*        second: 'text',
*      },
*      func: function(params) {
*        // do something with params.first and params.second here.
*      },
*   });
*
*
* Example 2: Sets a timer to run repeatedly at intervals of 5 seconds:
*
*   $('#someid').startTimer({
*      interval: 5000,
*      func: function() {
*        console.log('Running');
*      },
*   });
*
*
* Example 3: Sets a timer to run every 2 seconds, starting with a call
*       immediately.  However, since the function removes the element 
*       from the document on the first run, the timer only runs one time.  
*       The timer is cleared from the queue and never runs again.
*
*   $('#someid').startTimer({
*     intervalAfter: 2000,
*     func: function() {
*       console.log('Running.');
*       $(this).remove();
*     },
*   });
*
*
* Example 4: Clears the timers (delay, interval, sleep) on the matching element(s).
*
*   $('#someid').stopTimer();
*
*
* Example 5: Pausing of an existing timer.
*
*  <button id="btn">pause</button>
*  <script>
*      var start = new Date().getTime();
*      $('#btn').startTimer({
*        intervalAfter: 1000,
*        func: function() {
*          console.log(new Date().getTime() - start);
*        },
*      }).click(function() {
*        if ($(this).text() == 'paused') {
*          $(this).continueTimer().text('pause');
*        }else{
*          $(this).pauseTimer().text('paused');
*        }
*      });
*  </script>
*
* Example 6: Pausing during an Ajax call, with run information use.
*
* <script>
*      $('#btn').startTimer({
*        intervalAfter: 100,
*        params: { next_ajax: 1 },
*        func: function(params) {
*          // do something every 1/10 second
*
*          // ping the server every 60 seconds
*          if (new Date().getTime() > params.next_ajax) {
*            $(this).pauseTimer();          // pause processing until ajax return
*            $.ajax({
*              ...
*              success: function() {
*                // do something with ajax response
*
*                // reset the next ajax for 60 seconds from now
*                params.next_ajax = new Date().getTime() + 60000;
*                $('#btn').continueTimer(); // continue the interface update
*              }
*            });
*          }
*        },
*      });
* </script>
*/
(function($) {
  /** Sets a timer on the elements in the JQuery array. */
	$.fn.startTimer = function(options) {
	  // options defaults
		options = $.extend({
		  func: null,           // The function to be called by the timer.  Often, this is an inline anonymous
		                        // function in JQuery style like func: function() { ... }.  If your function
		                        // returns false, the timer will be cancelled.

		  params: {},           // The parameters to send to func when it is called.  To send multiple parameters
		                        // to your function, use a JavaScript object in JQuery-style: { key1=val1, key2=val2 }.

		  delay: -1,            // If >= 0, sets a one-time timer that is called after *delay* milliseconds.
		                        // The delay is 0 or less, no timer is set.

		  interval: -1,         // If >= 0, sets a repeating timer that is called every *interval* milliseconds.
		                        // If the interval is 0 or less, no timer is set.   Note that this option calls
		                        // your method every *interval* seconds regardless of how long your function takes
		                        // to run.  If the interval is 2 seconds and your function takes 1 second, the wait
		                        // period between calls will only be 1 second.  If your interval is 2 seconds and
		                        // your function takes 2 seconds, there will be no wait period at all.  For many
		                        // purposes, sleep is a better option than interval because it ensures yielding.
		                        
		  intervalAfter: -1,    // This option is the same as *interval*, but it waits after an initial call
		                        // to the function.  Normally, initial waits the specified period before the first
		                        // call.  This option immediately calls your function, followed by the interval,
		                        // then calls it again.
		                        
		  sleep: -1,            // If >= 0, sets a repeating timer that waits *sleep* milliseconds between calls.
		                        // If the sleep is 0 or less, no timer is set.  Note that this option will 
		                        // wait the entire sleep period between each call.  If your sleep is 2 seconds and your
		                        // function takes 1 second to run, there will be 3 seconds between each call
		                        // (the 2 second interval + 1 second function run time).  If your sleep is 2 seconds
		                        // and your function takes 2 seconds to run, there will be 4 seconds between each
		                        // call.  This is different than the interval option, which calls your method every 
		                        // *interval* seconds, regardless of how long your function runs.  
		                        // The sleep option is likely the better option because it ensures that *sleep* 
		                        // milliseconds are waited from the end of your function run to the beginning 
		                        // of the next run, ensureing that your function will yield time to other processes
		                        // regardless of how long the function takes. 

		  sleepAfter: -1,       // This option is the same as *sleep*, but it waits after an initial call
		                        // to the function.  Normally, initial waits the specified period before the first
		                        // call.  This option immediately calls your function, followed by the sleep,
		                        // then calls it again.  Mozilla developer docs call this option the "recursive setTimeout"
		                        // pattern.
		                        
		  unique: true,         // If unique=true, only one timer by the given name will exist at any given time.
		                        // This allows you to let this plugin manage the stopping of previous timers or
		                        // intervals for you.  If you call setTimer a second time before the previous timer 
		                        // has run out, the previous timer will be stopped before the new timer is set.  With
		                        // unique=true, you can always know that only one timer will be running at a time.
		                        // Note that "timers" includes both one-time and repeating timers -- if you need one
		                        // of each, give them different names.

		  name: TIMER_KEY,      // (optional) The name of this timer -- used to stop the timer after it is set.  Timers are 
		                        // unique to the name + DOM element, so two timers by the same name on a different 
		                        // element are two different timers, and two timers by the different names on
		                        // the same element are two different timers.  A default name is used for both 
		                        // setting and clearing timers on elements, so you can probably ignore this option.
		                        // The only reason to use timer names is if you need multiple timers on the same element 
		                        // that need to be stopped individually.
		}, options || {});

    // if unique, clear any timers by this name
    if (options.unique) {
      $(this).stopTimer();
    }//if
    
    // set the timeouts on every element in the JQuery array
    $(this).each(function() {
      saveTimerInfo(this, options);        // sets up the data structure so continue thinks we're a paused timer
      $(this).continueTimer(options.name);          // starts up the timer now that the options are in place
    });//main each loop
    
    // return this to allow chaining
    return this;
  };//setTimer function
  
  
  /** 
   *  Clears the timers with the given name on this element. If name is 
   *  not specified, the timers registered with the default name are cleared. 
   */
  $.fn.stopTimer = function(name) {
    name = name || TIMER_KEY;
    $(this).each(function() {
      stopTimerByName(this, name || TIMER_KEY);
      if ($(this).data(TIMER_KEY) && $(this).data(TIMER_KEY)[name]) {
        delete $(this).data(TIMER_KEY)[name]; // by entirely removing the object, we prevent it from running again and free memory used by the optiosn (especially the function)
      }//if
    });//each
    return this;
  };//clearTimer
  
  
  /** 
   *  Pauses the timers with the given name on this element.  If name is 
   *  not specified, the timers registered with the default name are paused.
   */
  $.fn.pauseTimer = function(name) {
    $(this).each(function() {
      name = name || TIMER_KEY;
      stopTimerByName(this, name);                      // clears the setTimeout
      if ($(this).data(TIMER_KEY) && $(this).data(TIMER_KEY)[name]) {
        $(this).data(TIMER_KEY)[name].timerid = false;  // stops the timer from continuing
      }//if
    });//each
    return this;
  }; 
  
  /** 
   *  Continues a paused timer with the given name on this element.  If name is
   *  not specified, the timer registered with the default name are continued.
   */
  $.fn.continueTimer = function(name) {
    name = name || TIMER_KEY;
    stopTimerByName(this, name);                      // clears a timeout if pause wasn't called first
    // for continue to work, the options must have been saved from a previous startTimer()
    // continuing is like to starting, but we already have the options (i.e. function to call) saved
    if ($(this).data(TIMER_KEY) && $(this).data(TIMER_KEY)[name] && $(this).data(TIMER_KEY)[name].options) {
      var options = $(this).data(TIMER_KEY)[name].options;
      $(this).each(function() {
        if (options.delay >= 0) {
          registerTimeout(this, options, options.delay);
        }else if (options.interval >= 0) {
          registerTimeout(this, options, options.interval);
        }else if (options.intervalAfter >= 0) {
          registerTimeout(this, options, 0);     // immediate call first
        }else if (options.sleep >= 0) {
          registerTimeout(this, options, options.sleep);
        }else if (options.sleepAfter >= 0) {
          registerTimeout(this, options, 0);     // immediate call first
        }//if
      });//each
    }//if
    return this;
  }; 
  
  ////////////////////////////////////////////////
  ///   Internal methods
  
  TIMER_KEY = 'jquery.easytimers.js';
  
  /* Does the actual setTimeout call. This is called from setTimer for the first run and then callWithElement for subsequent runs. */
  function registerTimeout(elem, options, duration) {
    if (duration > 0) { // call later
      saveTimerInfo(elem, options, window.setTimeout(function() { callWithElement(elem, options); }, duration));  // the anonymous function here is used because IE doesn't allow parameter passing like others
    }else{ // call immediately
      saveTimerInfo(elem, options, 'dummy'); // must set a non-real timerid so callWithElement continues the timer loop
      callWithElement(elem, options)
    }//if
  }//registerTimeout


  /* The event function that setTimeout calls.  It runs the user function, then continues the timer loop. */
  function callWithElement(elem, options) {
    var starttime = new Date().getTime();
    var ret = options.func.call(elem, options.params);
    stopTimerByName(elem, options.name);
    if (ret !== false && options.delay <= 0 && $(elem).data(TIMER_KEY) && $(elem).data(TIMER_KEY)[options.name] && $(elem).data(TIMER_KEY)[options.name].timerid) {
      if (options.interval >= 0) {
        registerTimeout(elem, options, Math.max(0, options.interval - (new Date().getTime() - starttime)));
      }else if (options.intervalAfter >= 0) {
        registerTimeout(elem, options, Math.max(0, options.intervalAfter - (new Date().getTime() - starttime)));
      }else if (options.sleep >= 0) {
        registerTimeout(elem, options, options.sleep);
      }else if (options.sleepAfter >= 0) {
        registerTimeout(elem, options, options.sleepAfter);
      }//if
    }//if
    return ret;
  }//callWithElement

  
  /** Save s a timer info */
  function saveTimerInfo(elem, options, timerid) {
    // ensure the info object exist for this name
    if (!$(elem).data(TIMER_KEY)) {
      $(elem).data(TIMER_KEY, {});
    }//if
    if (!$(elem).data(TIMER_KEY)[options.name]) {
      $(elem).data(TIMER_KEY)[options.name] = {}
    }//if
    $(elem).data(TIMER_KEY)[options.name].options = options;
    if (timerid) {
      $(elem).data(TIMER_KEY)[options.name].timerid = timerid;
    }//if
  }//saveTimerInfo
  
  /* Clears a timer or interval with the given name */
  function stopTimerByName(elem, name, clear) {
    // if we have a timer id, clear the timer 
    if ($(elem).data(TIMER_KEY) && $(elem).data(TIMER_KEY)[name] && $(elem).data(TIMER_KEY)[name].timerid) { 
      window.clearTimeout($(elem).data(TIMER_KEY)[name].timerid);
    }//if
  }//stopTimerByName
  
    /* Hook to JQuery's cleanData method so we can clear timers when DOM elements are removed */
  var oldClean = jQuery.cleanData;
  $.cleanData = function( elems ) {
   for ( var i = 0, elem; (elem = elems[i]) !== undefined; i++ ) {
     $(elem).stopTimer();
   }//for i
   oldClean(elems);
  };//cleanData  
  
  
})(jQuery);















