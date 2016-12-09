# ArcanistUncrustifyLinter

## What is Uncrustify

Uncrustify make code formatting beautiful. It needs configrations, I couldn't find a good configuration for iOS developer on Objective-C.

Here's a repo collecting pretty uncrustify config for iOS developer.

You can distribute your configurations over pull request.

## What is ArcanistUncrustifyLinter

when you use phabricator to manage your iOS projects, you can use ArcanistUncrustifyLinter with Uncrustify to help you format code.

## Requirements

1. Tested with Xcode 4.6+ (also works in Xcode 5) on OS X 10.7 or higher.
- Uncrustify 0.60 higher (0.60 has a bug for Objective-C block, so install master HEAD or higher in the future)
- BBUncrustifyPlugin-Xcode

## Installation

### Uncrustify

1. using [HomeBrew](http://mxcl.github.io/homebrew/) install Uncrustify
```
brew install uncrustify --HEAD
```
- install [BBUncrustifyPlugin-Xcode](https://github.com/benoitsan/BBUncrustifyPlugin-Xcode/blob/master/README.md#installation)
- clone this repo to `~/.uncrustify/` or other folder as [BBUncrustifyPlugin-Xcode](https://github.com/benoitsan/BBUncrustifyPlugin-Xcode/blob/master/README.md#how-to-customize-the-uncrustify-configuration) said.
```
git clone https://github.com/dijkst/uncrustify-config-ios.git ~/.uncrustify
```

### ArcanistUncrustifyLinter

A custom local install of the ArcanistUncrustifyLinter can be easily done:

```
arc set-config load "[ \"/absolute path/ArcanistUncrustifyLinter\" ]"
```

Check the installation:

```
arc linters  # should list ‘Uncrustify’ as ‘configured’
```

### Add a .uncurstify
Uncrustify accepts its format options which is given by `-c` flags. To provide the option as a hidden file, `.uncrustify` is recommended.

```
indent_with_tabs    = 0     # 1=indent to level only, 2=indent with tabs
input_tab_size      = 4     # original tab size
output_tab_size     = 4     # new tab size
indent_columns      = output_tab_size
indent_label        = 1     # pos: absolute col, neg: relative column
indent_switch_case  = 4     # number

#
# inter-symbol newlines
#

nl_enum_brace       = remove    # "enum {" vs "enum \n {"
nl_union_brace      = remove    # "union {" vs "union \n {"
nl_struct_brace     = remove    # "struct {" vs "struct \n {"
nl_do_brace         = remove    # "do {" vs "do \n {"
nl_if_brace         = remove    # "if () {" vs "if () \n {"
nl_for_brace        = remove    # "for () {" vs "for () \n {"
nl_else_brace       = remove    # "else {" vs "else \n {"
nl_while_brace      = remove    # "while () {" vs "while () \n {"
nl_switch_brace     = remove    # "switch () {" vs "switch () \n {"
nl_brace_while      = remove    # "} while" vs "} \n while" - cuddle while
nl_brace_else       = add       # "} \n else" vs "} else"
nl_func_var_def_blk = 1
nl_fcall_brace      = remove    # "list_for_each() {" vs "list_for_each()\n{"
nl_fdef_brace       = add       # "int foo() {" vs "int foo()\n{"

#
# Source code modifications
#

mod_paren_on_return = ignore    # "return 1;" vs "return (1);"
mod_full_brace_if   = add       # "if() { } else { }" vs "if() else"

#
# inter-character spacing options
#

sp_sizeof_paren     = remove    # "sizeof (int)" vs "sizeof(int)"
sp_before_sparen    = force     # "if (" vs "if("
sp_after_sparen     = force     # "if () {" vs "if (){"
sp_inside_braces    = add       # "{ 1 }" vs "{1}"
sp_inside_braces_struct = add   # "{ 1 }" vs "{1}"
sp_inside_braces_enum   = add   # "{ 1 }" vs "{1}"
sp_assign           = add
sp_arith            = add
sp_bool             = add
sp_compare          = add
sp_assign           = add
sp_after_comma      = add
sp_func_def_paren   = remove    # "int foo (){" vs "int foo(){"
sp_func_call_paren  = remove    # "foo (" vs "foo("
sp_func_proto_paren = remove    # "int foo ();" vs "int foo();"
sp_else_brace       = add       # ignore/add/remove/force
sp_before_ptr_star  = add       # ignore/add/remove/force
sp_after_ptr_star   = remove    # ignore/add/remove/force
sp_between_ptr_star = remove    # ignore/add/remove/force
sp_inside_paren     = remove    # remove spaces inside parens
sp_paren_paren      = remove    # remove spaces between nested parens
sp_inside_sparen    = remove    # remove spaces inside parens for if, while and the like

#
# Aligning stuff
#

align_with_tabs     = FALSE     # use tabs to align
align_on_tabstop    = TRUE      # align on tabstops
align_enum_equ_span = 4         # '=' in enum definition
align_struct_init_span  = 0     # align stuff in a structure init '= { }'
align_right_cmt_span    = 3
```

### Update .arcconfig

```
"lint.engine": "ArcanistConfigurationDrivenLintEngine"
```

### Add a .arclint file

```
{
  "linters": {
    "c-lang": {
      "type": "Uncrustify",
      "version": ">=0.70",
      "flags": ["-c", ".uncrustify", "-f"],
      "include": "(\\.(c|h)$)"
    }
  }
}
```

### Custom

Default settings disable the alignment of continued assignment or variable definition. If you need them, just set `align_assign_span`, `align_var_def_span` and `align_oc_msg_spec_span` to `1`.


## Example

#### ~~align code~~ (unsupported, please read `Custom`):

before:
``` objective-c
NSString *const BBUncrustifyOptionEvictCommentInsertion = @"evictCommentInsertion";
NSString *const BBUncrustifyOptionSourceFilename = @"sourceFilename";
NSString *const BBUncrustifyOptionSupplementalConfigurationFolders = @"supplementalConfigurationFolders";
```
after:
``` objective-c
NSString *const BBUncrustifyOptionEvictCommentInsertion            = @"evictCommentInsertion";
NSString *const BBUncrustifyOptionSourceFilename                   = @"sourceFilename";
NSString *const BBUncrustifyOptionSupplementalConfigurationFolders = @"supplementalConfigurationFolders";
```

#### remove space:

before:
``` objective-c
-( void )viewWillEnter ;
```
after:
``` objective-c
- (void)viewWillEnter;
```

#### insert newline between methods

``` objective-c
- (void)a {
}
- (void)b{
}
```
after:
``` objective-c
- (void)a {
}

- (void)b {
}
```

and so on.

## License

uncrustify-config-ios is available under the MIT license.


# Demo uncrustify-config

https://github.com/EmoneyCN/uncrustify-config-ios
