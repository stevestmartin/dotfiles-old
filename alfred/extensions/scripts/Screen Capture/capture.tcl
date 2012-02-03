####################################################################
# Name:     capture.tcl
# Author:   Trevor Williams  (phase1geo@gmail.com)
# Date:     9/10/2011
# Version:  1.1
# Description: 
#           Provides various types of screen capturing capabilities.
####################################################################

set version 1.1

###########################
# Display usage information 
proc usage {} {

  puts "Capture Help (ver. $::version)"
  puts "--------------------------"
  puts "capture screen"
  puts "  - Captures the screen and outputs"
  puts "    it to a file called 'capture.png'"
  puts "    on your Desktop"
  puts "" 
  puts "capture screen to <file>"
  puts "  - Captures the screen and outputs"
  puts "    it to a file called <file>"
  puts ""
  puts "capture screen to clipboard"
  puts "  - Captures the screen and saves the"
  puts "    image to the clipboard"
  puts ""
  puts "capture selection"
  puts "  - Captures a screen selection and"
  puts "    outputs it to a file called"
  puts "    'capture.png' on your Desktop"
  puts ""
  puts "capture selection to <file>"
  puts "  - Captures a screen selection and"
  puts "    outputs it to a file called <file>"
  puts ""
  puts "capture selection to clipboard"
  puts "  - Captures the screen and saves the"
  puts "    image to the clipboard"
  puts ""
  puts "capture window"
  puts "  - Captures a selected window and"
  puts "    outputs it to a file called"
  puts "    'capture.png' on your Desktop"
  puts ""
  puts "capture window to <file>"
  puts "  - Captures a selected window and"
  puts "    outputs it to a file called <file>"
  puts ""
  puts "capture window to clipboard"
  puts "  - Captures a selected window and"
  puts "    saves the image to the clipboard"
  puts ""
  puts "capture help"
  puts "  - Displays help for capture"
  puts "    command"

}

###################################################################
# Returns the name of the file to use when no filename is specified
proc get_auto_name {} {

  set filename [file join ~ Desktop capture.png]
  set i 1

  while {[file exists $filename]} {
    set filename [file join ~ Desktop capture$i.png]
    incr i
  }

  return [file normalize $filename]

}

# The arguments will be stored as a list in index 0 of the command-line arguments variable
set argv [lindex $argv 0]

# If the user wants a screen capture, do so.
if {[string range [lindex $argv 0] 0 1] eq "sc"} {
  if {[llength $argv] == 1} {
    eval "exec screencapture -S [get_auto_name]"
  } elseif {[lindex $argv 1] eq "to"} {
    if {[lindex $argv 2] eq "clipboard"} {
      exec screencapture -cS
    } elseif {[lindex $argv 2] ne ""} {
      eval "exec screencapture -S [file normalize [lindex $argv 2]]"
    } else {
      usage
    }
  } else {
    if {[lindex $argv 1] eq "clipboard"} {
      exec screencapture -cS
    } elseif {[lindex $argv 1] ne ""} {
      eval "exec screencapture -S [file normalize [lindex $argv 1]]"
    } else {
      usage
    }
  }

# If the user wants a screen selection, do so.
} elseif {[string range [lindex $argv 0] 0 1] eq "se"} {
  if {[llength $argv] == 1} {
    eval "exec screencapture -s [get_auto_name]"
  } elseif {[lindex $argv 1] eq "to"} {
    if {[lindex $argv 2] eq "clipboard"} {
      exec screencapture -cs
    } elseif {[lindex $argv 2] ne ""} {
      eval "exec screencapture -s [file normalize [lindex $argv 2]]"
    } else {
      usage
    }
  } else {
    if {[lindex $argv 1] eq "clipboard"} {
      exec screencapture -cs
    } elseif {[lindex $argv 1] ne ""} {
      eval "exec screencapture -s [file normalize [lindex $argv 1]]"
    } else {
      usage
    }
  }

# If the user wants a window selection, do so.
} elseif {[string index [lindex $argv 0] 0] eq "w"} {
  if {[llength $argv] == 1} {
    eval "exec screencapture -iW [get_auto_name]"
  } elseif {[lindex $argv 1] eq "to"} {
    if {[lindex $argv 2] eq "clipboard"} {
      exec screencapture -ciW
    } elseif {[lindex $argv 2] ne ""} {
      eval "exec screencapture -iW [file normalize [lindex $argv 2]]"
    } else {
      usage
    }
  } else {
    if {[lindex $argv 1] eq "clipboard"} {
      exec screencapture -ciW
    } elseif {[lindex $argv 1] ne ""} {
      eval "exec screencapture -iW [file normalize [lindex $argv 1]]"
    } else {
      usage
    }
  }

# If the user enters anything else, display the help
} else {
  usage
}

