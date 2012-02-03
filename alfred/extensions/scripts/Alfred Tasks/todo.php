<?php

// Create new todo.xml if one isn't found
if (!file_exists("todo.xml")) {
	
		// create the base element
		$todoList = new SimpleXMLElement("<todolist></todolist>");

		// create all child elements
		$l = $todoList->addChild("list");
		$l->addChild("name", "Todo");
		$l->addChild("todo", "Sample Task");
		$todoList->addChild("defaultGroup", "Todo");

		// save the xml to the settings.xml file
		$todoList->asXML("todo.xml");
		unset($todoList);

}

// Break the input passed in into an array for parsing
$command = explode(" ", $argv[1]);

if ($command[0] == "about" && $command[1] == "") {
	echo "Alfred Tasks is a simple todo list created by David Ferguson (@jdfwarrior) for Alfred. It's a quick and simple way to manage tasks. Do you sync your Alfred preferences using Dropbox or some other services? Then your Alfred Tasks todo list is synced across all of your computers as well.\r\rType 'do help' for a list of all commands now.";
	exit(1);
}

if ($command[0] == "version" && $command[1] == "") {
	if (file_exists("update.xml")) {
		$version = simplexml_load_file("update.xml");
		echo "Alfred Tasks $version->version";
		unset($version);
		exit(1);
	}
	else {
		echo "No version information found for this extension.";
	}
}

if ($command[0] == "help" && $command[1] == "") { //display help
	
	echo "Alfred Tasks Help\n";
	echo "----------------------------\n\n";
	echo "<g> Is a group name\n <t> Is a task\n <n> Is a task number/id\n\n";
	echo "Commands:\n";
	echo "do <t> - Add to default group\n";
	echo "do <g> <t> - Add to specific group\n";
	echo "do list - List tasks in default group\n";
	echo "do list all - List all tasks and groups\n";
	echo "do list <g> - List tasks in group\n";
	echo "do group <g> - Create new group\n";
	echo "do rename <g> <new> - Rename group\n";
	echo "do default <g> - Set group as default\n";
	echo "do rem <g> - Remove group\n";
	echo "do rem <g> <n> - Remove task in group\n";
	echo "do rem <g> all - Remove all in group\n\n";
	echo "If only one group exists, you do not need to specify the group number when deleting a task. Alfred Tasks will use the default group.\n\n";
	echo "do help - Display this help menu\n";
	echo "do about - Display About info\n";
	echo "do version - Display extension version\n";
	echo "do changelog - Display changelog";
	
	exit(1);
}

if ($command[0] == "changelog" && $command[1] == "") {
		
	// if changelog exists, show contents
	if (file_exists("changelog.txt")) {
		$f = fopen("changelog.txt", "r");
		while ($output = fgets($f)) {
			echo $output;
		}
		fclose($f);
	}

	// if changelog doesn't exist, display error
	else {
		echo "No changelog found.";
	}

	exit(1);
}

// Load the todo list file
$todo = simplexml_load_file("todo.xml");

// Read the default group from the xml file
$defaultGroup = $todo->defaultGroup;

// List all items
if ($command[0] == "list") { 
	
	// List all tasks in all groups
	if ($command[1] == 'all') {
		
		// Count the number of todo lists
		$count = count($todo->list);
		$index = 0;

		// Loop through every group
		foreach($todo->list as $list): 
			
			// If the current group is the default, append the title
			if (strtolower($list->name) == strtolower($defaultGroup)) { 
				echo ucfirst($list->name)." (default)\n";
			}

			// If it's not the default group, just list it
			else {
				echo ucfirst($list->name)."\n";
			}
			
			$i=1;
			
			// If there are no tasks in this group, display message
			if (count($list->todo) == 0) { 
				echo "No tasks in ".$list->name."\n";
			}

			// List all tasks in this group
			else { 

				foreach($list->todo as $task):
					echo $i." - ".ucfirst($task)."\n";
					$i++;
				endforeach;

			}
			
			$index++;

			//checks to see if this is the last group so the final return can be avoided
			if ($index != $count) {  echo  "\n"; }
			
		endforeach; //end foreach (groups)
		
	}

	//List all groups
	else if ($command[1] == 'groups') {
		
		$i=1;

		// Iterate through and list all groups
		foreach($todo->list as $list):

			// If current group is default, append the title
			if (strtolower($list->name) == strtolower($defaultGroup)) { 
				echo $i." - ".ucfirst($list->name)." (default)\n";
			}

			// If its not the default, just list it
			else {
				echo $i." - ".ucfirst($list->name)."\n";
			}

			$i++;

		endforeach; //end foreach (groups)
		
	}

	// List tasks in a specific group, or the default group
	else { 
		
		// If no group specified, use the default group
		if ($command[1] == "") { $group = $defaultGroup; }
		else { $group = $command[1]; }
		
		// If the name passed is a valid group
		if (is_group($group, $todo)) {
			
			// Grab group index in the list
			$index = group_index($group, $todo);
			$i=1;
			
			// Display msg if no tasks exist in the group
			if (count($todo->list[$index]->todo) == 0) {
				echo "No tasks in ".ucfirst($group)."!"; exit(1); }
			
			// Loop through all tasks in the selected group
			foreach($todo->list[$index]->todo as $item):
			
				echo $i.". ".$item."\n";
				$i++;
			
			endforeach;

		} //end if is_group
		
		//If not a valid group
		else {
			echo "Unable to find the task group \"$group\". Please use 'do list groups' to get a list of all tasks groups.";
		}
	}
	
}
else if ($command[0] == "group") { //Create new group
	
	$group = $command[1];
	
	//Make sure a new group name was specified, display erorr if not
	if ($group == "") { 
		echo "You must specify a name for the new group."; exit(1); }
	
	$group = str_replace("&", "and", $group);
	
	//Create the new group element
	$list = $todo->addChild('list');
	$list->addChild('name', ucfirst(strtolower($group)));
	
	//Save xml
	$todo->asXML("todo.xml");
	
	echo "Task Group Added: $command[1]";
	
}
else if ($command[0] == "task") { //Create new task
	
	//If group name is empty, use the default group, otherwise, use passed name value
	$group = ($command[1] == "") ? $defaultGroup : $command[1];

	//Grab task text
	for($i=2; $i<count($command); $i++) {
		$task .= $command[$i]." "; }	

	//If group specified is a valid group
	if (is_group($group, $todo)) {
		
		//Create new task item
		$task = str_replace("&", "and", $task);
		$index = group_index($group, $todo);
		$todo->list[$index]->addChild('todo', $task);
		
		//Save xml
		$todo->asXML("todo.xml");
			
		echo "Task Added: $task";
	}
	else {
		echo "Unable to find the task group specified. Please use 'todo groups' to get a list of all tasks groups."; }
		
}
else if ($command[0] == "rem") {
	
	$groupCount = count($todo->list); //Count total number of groups
	$multiple_pattern = "/([0-9]+[,]?)+/";
	
	if ($groupCount == 1) { //single group
		
		$group = $defaultGroup; //set the current group
		unset($command[0]);	//remove the command from the array
		$string = implode("", $command); //implode the commands array to determine its format
		
		//If its a list of items to delete
		if (preg_match($multiple_pattern,$string)) { //remove array of items
			
			$index = group_index($group, $todo);
			$tasks = explode(",", $string); //explode on commas to get each item index
			
			//Reverse sort the array of items. This is done so that it removes from the bottom first. If you remove from the top first, then
			//the item index is thrown off on subsequent removes. By removing from the end first, it eliminates this.
			rsort($tasks); 
			
			//Loop through each task to remove, and remove it.
			foreach($tasks as $task):
				$task -= 1;
				if ($todo->list[$index]->todo[$task]) { //task exists, delete it
					unset($todo->list[$index]->todo[$task]);		
					echo "Removed task ".($task+1)." in $group\n";
				}
				else { //Specified task to remove doesn't exist
					echo "Invalid task. Please type 'do list' for a list of all tasks in this group."; 
				}
			endforeach;
			
			//Save xml
			$todo->asXML("todo.xml");
			
		}
		else if (is_numeric($command[1])) { //numeric input, remove task from default group (single group)
			
			$index = group_index($group, $todo); 
			$task = $command[1];
			$task -= 1; //decrement for zero based array index
			
			if ($todo->list[$index]->todo[$task]) { //task exists, delete it
				
				unset($todo->list[$index]->todo[$task]);
				
				//Save xml
				$todo->asXML("todo.xml");
					
				echo "Removed task ".($task+1)." in $group"	;
				
			}
			else { //Specified task to remove doesn't exist
				echo "Invalid task. Please type 'do list' for a list of all tasks in this group."; 
			}
			
		}
		else { //non-numeric specified, remove group. (single group)
			
			if ($command[1] == 'all') { //remove all tasks from default group (single group)
				
				$index = group_index($group, $todo);
				
				unset($todo->list[$index]);
				$list = $todo->addChild('list');
				$list->addChild('name', $group);
				
				//Save xml
				$todo->asXML("todo.xml");
					
				echo "Removed all tasks in $group";
				
			}
			//Tried to remove the default group
			if (strtolower($command[1]) == strtolower($defaultGroup)) {
				echo "You cannot remove the default group.";
			}
			else { //Tried to remove... ????
				echo "Invalid input. Type 'do help' for a list of all commands.";
			}
			exit(1);
			
		}
		
	}
	else { //more than a single group
		
		if (is_numeric($command[1])) {
			echo "When multiple groups are present, you must specify the group you wish to remove the task from. Use 'do help' to command help."; 
		}
		else {
			
			if ($command[2] == "") { //only one parameter passed, assuming its a group
				
				$group = $command[1];
				if (is_group($group, $todo)) { //valid group, delete it
					
					$index = group_index($group, $todo);
					unset($todo->list[$index]);
					
					//Save xml
					$todo->asXML("todo.xml");
						
					echo "Removed group ".ucfirst($group);
					
				}
				else {
					echo "Invalid group. Please type 'do list groups' for a list of all groups."; 
				}
				
			}
			else { //two parameters passed, assuming its a group then task
				
				$group = $command[1];
				if (is_group($group, $todo)) { //valid group
				
					$multiple_pattern = "/([0-9]+[,]?)+/";
					unset($command[0]);
					unset($command[1]);
					$tasks = implode("", $command);
					
					if (preg_match($multiple_pattern,$tasks)) { //remove array of items

						$index = group_index($group, $todo);
						$tasks = explode(",", $tasks); //explode on commas to get each item index

						//Reverse sort the array of items. This is done so that it removes from the bottom first. If you remove from the top first, then
						//the item index is thrown off on subsequent removes. By removing from the end first, it eliminates this.
						rsort($tasks); 

						//Loop through each task to remove, and remove it.
						foreach($tasks as $task):
							$task -= 1;
							if ($todo->list[$index]->todo[$task]) { //task exists, delete it
								unset($todo->list[$index]->todo[$task]);		
								echo "Removed task ".($task+1)." in $group\n";
							}
							else { //Specified task to remove doesn't exist
								echo "Invalid task. Please type 'do list' for a list of all tasks in this group."; 
							}
						endforeach;

						//Save xml
						$todo->asXML("todo.xml");

					}
					
					else if (is_numeric($task)) { //numeric task id
						
						$task -= 1;
						$index = group_index($group, $todo);
					
						if ($todo->list[$index]->todo[$task]) { //task exists, delete it
					
							unset($todo->list[$index]->todo[$task]);
						
							//Save xml
							$todo->asXML("todo.xml");
					
							echo "Removed task ".($task+1)." in $group"	;
					
						}

					}
					
					else if ($task == 'all') {
						
						$index = group_index($group, $todo);
						unset($todo->list[$index]);
						$list = $todo->addChild('list');
						$list->addChild('name', $group);
						
						//Save xml
						$todo->asXML("todo.xml");
						
						echo "Removed all tasks in $group";
						
					}
					
					else {
						echo "Invalid task. Please type 'do list $group' for a list of all tasks in this group."; 
					}
					
				}
				else {
					echo "Invalid group. Please type 'do list groups' for a list of all groups."; 
				} 
				
			} //end else (multiple parameters passed)
			
		} //end else (non numeric $command[1])
		
	} //end else (more than one group exists)
	
}
else if ($command[0] == "default") { //set the default group
	
	$group = $command[1];
	if (is_group($group, $todo)) { //valid group
		
		unset($todo->defaultGroup);
		$todo->addChild('defaultGroup', ucfirst($group));
	
		//Save xml
		$todo->asXML("todo.xml");
		
		echo "Group ".ucfirst($group)." is now set as the default group.";
		
	}
	else {
		echo "Invalid group $group. Please type 'do list groups' for a list of all groups.";
	}
	
}
else if ($command[0] == "rename") { //rename group 
	
	$group = $command[1];
	if (is_group($group, $todo)) { //valid group
		
		$name = $command[2];
		$name = str_replace("&", "and", $name);
		$index = group_index($group, $todo);
		unset($todo->list[$index]->name);
		$todo->list[$index]->addChild('name', $name);
		
		if ($group == $defaultGroup) { //if renamed default group, set new defaultGrouup value
			unset($todo->defaultGroup);
			$todo->addChild('defaultGroup', $name);
		}
		
		//Save xml
		$todo->asXML("todo.xml");
			
		echo "Renamed Group $group to $name";
		
	}
	else {
		echo "Invalid group $group. Please type 'do list groups' for a list of all groups.";
	}
}
else if ($command[0] == "move") {
	if (count($todo->list) == 1) {
		echo "You cannot move a task when only one group exists";
	}
	else {
		if ($command[1] != "" && $command[2] != "" && is_numeric($command[2]) && $command[3] != "") {
			$group_source = $command[1];
			$task_id = $command[2]-1;
			$group_target = $command[3];
			
			if ( ! is_group( $group_source, $todo ) ) {
				echo "Invalid source group. Please type 'do list groups' for a list of all valid groups."; 
			}
			else if ( ! is_group( $group_target, $todo ) ) {
				echo "Invalid target group. Please type 'do list groups' for a list of all valid groups."; 
			}
			
			$source_index = group_index($group_source, $todo);
			$target_index = group_index($group_target, $todo);
			
			if ( $todo->list[$source_index]->todo[$task_id] != "" ) {
				
				$task = $todo->list[$source_index]->todo[$task_id];
				unset($todo->list[$source_index]->todo[$task_id]);
				$item = $todo->list[$target_index]->addChild('todo', "$task");

				//Save xml
				$todo->asXML("todo.xml");

				echo "Moved Task: $task to $group_target";
			}
			else {
				echo "Invalid task id. Please type 'do list $group_source' to list all tasks in the source group.";
			}
			
		}
		else {
			echo "Syntax error. Please type 'do help' to view the syntax for the move command."; 
		}
	}
} 
else { //insert task
	
	$group = $command[0];
	$task="";
	
	if (is_group($group, $todo)) { //specified group
		for($i=1; $i<count($command); $i++) {
			$task .= $command[$i]." "; }
	}
	else { //no group specified, use default
		$group = $defaultGroup;
		for($i=0; $i<count($command); $i++) {
			$task .= $command[$i]." "; }
	}
	
	$index = group_index($group, $todo);
	
	if ($index >= 0) { //group was found
		
		$task = str_replace("&", "and", $task);
		$list = $todo->list[$index]->addChild('todo', "$task");
		
		//Save xml
		$todo->asXML("todo.xml");
			
		echo "Added New Task: $task";
		
	}
	else { 
		echo "Invalid group $group. Please type 'do list groups' for a list of all groups."; 
	}
}


//iterates over xml to determine if the value passed is a valid group
function is_group($name, $xml) {
	$found = false;
	foreach($xml->list as $list):
		if (strtolower($list->name) == strtolower($name)) { $found = true; }
	endforeach;
	return $found;
}

//iterates over xml and returns the index of the group if found
function group_index($name, $xml) {
	$i=0;
	foreach($xml->list as $list):
		if (strtolower($list->name) == strtolower($name)) { return $i; }
		$i++;
	endforeach;
	return -1;
}
