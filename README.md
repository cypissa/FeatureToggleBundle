# FeatureToggle Bundle

The bundle provides following features:

* Toggle upon a feature (on view side, and backend side)
* Enabling a feature based on probability
* Request/Session/User based feature enabling

# Usage

To add a toggle for the feature:

## Step 1

Install the bundle :)

## Step 2

Add in configuration file:

```
  cogi_feature_toggle:
      features:
          feature_we_want_toggle_name:
              likelihood: 50 # in percents, it means likelihood that feature will be enable across request or session (depends on next parameter)
              throughout_session: true # if set to true then once the mechanism enable/disable the feature it's enabled/disabled across entire session
              users: # set users to which the feature will be enabled. For the rest feature will be disabled. Leave this parameter if don't want the feature depend on particular users
                  - 'user1'
                  - 'user2'
          another_feature:
              # ...
```

## Step 3

Toggle a feature from php:

```
  if ($serviceContainer->get('toggler')->isEnabled('feature_we_want_toggle_name')) {
    // feature based code
  }
```

Toggle a feature from twig:

```
  {% if is_feature_enabled('feature_we_want_toggle_name') %}
    {# feature based code #}
  {% endif %}
```

# Tips

* If you want enable a feature every time it's requested set likelihood to 100
* If you want disable a feature every time it's requested set likelihood to 0

# Known issues

* Request wide toggling may give different result when there are more than one "isEnabled" method call for one feature across the same request
    * Eg. Likelihood is 50. It may happend than on the backed isEnabled('the_feature') would return true, and the next call false
    * The problem won't happend if "throughout_session" is set to true
    * A little refactor is needed
