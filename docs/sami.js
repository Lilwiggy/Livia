
window.projectVersion = 'master';

(function(root) {

    var bhIndex = null;
    var rootPath = '';
    var treeHtml = '        <ul>                <li data-name="namespace:CharlotteDunois" class="opened">                    <div style="padding-left:0px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="CharlotteDunois.html">CharlotteDunois</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="namespace:CharlotteDunois_Livia" class="opened">                    <div style="padding-left:18px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="CharlotteDunois/Livia.html">Livia</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="namespace:CharlotteDunois_Livia_Arguments" >                    <div style="padding-left:36px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="CharlotteDunois/Livia/Arguments.html">Arguments</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:CharlotteDunois_Livia_Arguments_Argument" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="CharlotteDunois/Livia/Arguments/Argument.html">Argument</a>                    </div>                </li>                            <li data-name="class:CharlotteDunois_Livia_Arguments_ArgumentCollector" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="CharlotteDunois/Livia/Arguments/ArgumentCollector.html">ArgumentCollector</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="namespace:CharlotteDunois_Livia_Commands" >                    <div style="padding-left:36px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="CharlotteDunois/Livia/Commands.html">Commands</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:CharlotteDunois_Livia_Commands_Command" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="CharlotteDunois/Livia/Commands/Command.html">Command</a>                    </div>                </li>                            <li data-name="class:CharlotteDunois_Livia_Commands_CommandGroup" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="CharlotteDunois/Livia/Commands/CommandGroup.html">CommandGroup</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="namespace:CharlotteDunois_Livia_Exceptions" >                    <div style="padding-left:36px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="CharlotteDunois/Livia/Exceptions.html">Exceptions</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:CharlotteDunois_Livia_Exceptions_CommandFormatException" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="CharlotteDunois/Livia/Exceptions/CommandFormatException.html">CommandFormatException</a>                    </div>                </li>                            <li data-name="class:CharlotteDunois_Livia_Exceptions_FriendlyException" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="CharlotteDunois/Livia/Exceptions/FriendlyException.html">FriendlyException</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="namespace:CharlotteDunois_Livia_Providers" >                    <div style="padding-left:36px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="CharlotteDunois/Livia/Providers.html">Providers</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:CharlotteDunois_Livia_Providers_MySQLProvider" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="CharlotteDunois/Livia/Providers/MySQLProvider.html">MySQLProvider</a>                    </div>                </li>                            <li data-name="class:CharlotteDunois_Livia_Providers_SettingProvider" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="CharlotteDunois/Livia/Providers/SettingProvider.html">SettingProvider</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="namespace:CharlotteDunois_Livia_Types" >                    <div style="padding-left:36px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="CharlotteDunois/Livia/Types.html">Types</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:CharlotteDunois_Livia_Types_ArgumentType" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="CharlotteDunois/Livia/Types/ArgumentType.html">ArgumentType</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="namespace:CharlotteDunois_Livia_Utils" >                    <div style="padding-left:36px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="CharlotteDunois/Livia/Utils.html">Utils</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:CharlotteDunois_Livia_Utils_DataHelpers" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="CharlotteDunois/Livia/Utils/DataHelpers.html">DataHelpers</a>                    </div>                </li>                            <li data-name="class:CharlotteDunois_Livia_Utils_FileHelpers" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="CharlotteDunois/Livia/Utils/FileHelpers.html">FileHelpers</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="class:CharlotteDunois_Livia_CommandDispatcher" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="CharlotteDunois/Livia/CommandDispatcher.html">CommandDispatcher</a>                    </div>                </li>                            <li data-name="class:CharlotteDunois_Livia_CommandMessage" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="CharlotteDunois/Livia/CommandMessage.html">CommandMessage</a>                    </div>                </li>                            <li data-name="class:CharlotteDunois_Livia_CommandRegistry" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="CharlotteDunois/Livia/CommandRegistry.html">CommandRegistry</a>                    </div>                </li>                            <li data-name="class:CharlotteDunois_Livia_LiviaClient" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="CharlotteDunois/Livia/LiviaClient.html">LiviaClient</a>                    </div>                </li>                            <li data-name="class:CharlotteDunois_Livia_LiviaClientEvents" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="CharlotteDunois/Livia/LiviaClientEvents.html">LiviaClientEvents</a>                    </div>                </li>                </ul></div>                </li>                </ul></div>                </li>                </ul>';

    var searchTypeClasses = {
        'Namespace': 'label-default',
        'Class': 'label-info',
        'Interface': 'label-primary',
        'Trait': 'label-success',
        'Method': 'label-danger',
        '_': 'label-warning'
    };

    var searchIndex = [
                    
            {"type": "Namespace", "link": "CharlotteDunois.html", "name": "CharlotteDunois", "doc": "Namespace CharlotteDunois"},{"type": "Namespace", "link": "CharlotteDunois/Livia.html", "name": "CharlotteDunois\\Livia", "doc": "Namespace CharlotteDunois\\Livia"},{"type": "Namespace", "link": "CharlotteDunois/Livia/Arguments.html", "name": "CharlotteDunois\\Livia\\Arguments", "doc": "Namespace CharlotteDunois\\Livia\\Arguments"},{"type": "Namespace", "link": "CharlotteDunois/Livia/Commands.html", "name": "CharlotteDunois\\Livia\\Commands", "doc": "Namespace CharlotteDunois\\Livia\\Commands"},{"type": "Namespace", "link": "CharlotteDunois/Livia/Exceptions.html", "name": "CharlotteDunois\\Livia\\Exceptions", "doc": "Namespace CharlotteDunois\\Livia\\Exceptions"},{"type": "Namespace", "link": "CharlotteDunois/Livia/Providers.html", "name": "CharlotteDunois\\Livia\\Providers", "doc": "Namespace CharlotteDunois\\Livia\\Providers"},{"type": "Namespace", "link": "CharlotteDunois/Livia/Types.html", "name": "CharlotteDunois\\Livia\\Types", "doc": "Namespace CharlotteDunois\\Livia\\Types"},{"type": "Namespace", "link": "CharlotteDunois/Livia/Utils.html", "name": "CharlotteDunois\\Livia\\Utils", "doc": "Namespace CharlotteDunois\\Livia\\Utils"},
            {"type": "Interface", "fromName": "CharlotteDunois\\Livia", "fromLink": "CharlotteDunois/Livia.html", "link": "CharlotteDunois/Livia/LiviaClientEvents.html", "name": "CharlotteDunois\\Livia\\LiviaClientEvents", "doc": "&quot;Documents all LiviaClient events (exlucing events from Yasmin).&quot;"},
                                                        {"type": "Method", "fromName": "CharlotteDunois\\Livia\\LiviaClientEvents", "fromLink": "CharlotteDunois/Livia/LiviaClientEvents.html", "link": "CharlotteDunois/Livia/LiviaClientEvents.html#method_commandBlocked", "name": "CharlotteDunois\\Livia\\LiviaClientEvents::commandBlocked", "doc": "&quot;Emitted when a command is prevented from running.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\LiviaClientEvents", "fromLink": "CharlotteDunois/Livia/LiviaClientEvents.html", "link": "CharlotteDunois/Livia/LiviaClientEvents.html#method_commandError", "name": "CharlotteDunois\\Livia\\LiviaClientEvents::commandError", "doc": "&quot;Emitted when a command produces an error while running.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\LiviaClientEvents", "fromLink": "CharlotteDunois/Livia/LiviaClientEvents.html", "link": "CharlotteDunois/Livia/LiviaClientEvents.html#method_commandRun", "name": "CharlotteDunois\\Livia\\LiviaClientEvents::commandRun", "doc": "&quot;Emitted when running a command.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\LiviaClientEvents", "fromLink": "CharlotteDunois/Livia/LiviaClientEvents.html", "link": "CharlotteDunois/Livia/LiviaClientEvents.html#method_unknownCommand", "name": "CharlotteDunois\\Livia\\LiviaClientEvents::unknownCommand", "doc": "&quot;Emitted when an user tries to use an unknown command.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\LiviaClientEvents", "fromLink": "CharlotteDunois/Livia/LiviaClientEvents.html", "link": "CharlotteDunois/Livia/LiviaClientEvents.html#method_commandRegister", "name": "CharlotteDunois\\Livia\\LiviaClientEvents::commandRegister", "doc": "&quot;Emitted when a command is registered.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\LiviaClientEvents", "fromLink": "CharlotteDunois/Livia/LiviaClientEvents.html", "link": "CharlotteDunois/Livia/LiviaClientEvents.html#method_commandReregister", "name": "CharlotteDunois\\Livia\\LiviaClientEvents::commandReregister", "doc": "&quot;Emitted when a command is re-registered.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\LiviaClientEvents", "fromLink": "CharlotteDunois/Livia/LiviaClientEvents.html", "link": "CharlotteDunois/Livia/LiviaClientEvents.html#method_commandUnregister", "name": "CharlotteDunois\\Livia\\LiviaClientEvents::commandUnregister", "doc": "&quot;Emitted when a command is unregistered.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\LiviaClientEvents", "fromLink": "CharlotteDunois/Livia/LiviaClientEvents.html", "link": "CharlotteDunois/Livia/LiviaClientEvents.html#method_groupRegister", "name": "CharlotteDunois\\Livia\\LiviaClientEvents::groupRegister", "doc": "&quot;Emitted when a group is registered.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\LiviaClientEvents", "fromLink": "CharlotteDunois/Livia/LiviaClientEvents.html", "link": "CharlotteDunois/Livia/LiviaClientEvents.html#method_typeRegister", "name": "CharlotteDunois\\Livia\\LiviaClientEvents::typeRegister", "doc": "&quot;Emitted when an argument type is registered.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\LiviaClientEvents", "fromLink": "CharlotteDunois/Livia/LiviaClientEvents.html", "link": "CharlotteDunois/Livia/LiviaClientEvents.html#method_commandPrefixChange", "name": "CharlotteDunois\\Livia\\LiviaClientEvents::commandPrefixChange", "doc": "&quot;Emitted whenever a guild&#039;s command prefix is changed. Guild will be null if the prefix is global. Prefix will be null if it is changed to default.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\LiviaClientEvents", "fromLink": "CharlotteDunois/Livia/LiviaClientEvents.html", "link": "CharlotteDunois/Livia/LiviaClientEvents.html#method_commandStatusChange", "name": "CharlotteDunois\\Livia\\LiviaClientEvents::commandStatusChange", "doc": "&quot;Emitted whenever a command is enabled\/disabled in a guild. Guild will be null if status is global.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\LiviaClientEvents", "fromLink": "CharlotteDunois/Livia/LiviaClientEvents.html", "link": "CharlotteDunois/Livia/LiviaClientEvents.html#method_groupStatusChange", "name": "CharlotteDunois\\Livia\\LiviaClientEvents::groupStatusChange", "doc": "&quot;Emitted whenever a group is enabled\/disabled in a guild. Guild will be null if status is global.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\LiviaClientEvents", "fromLink": "CharlotteDunois/Livia/LiviaClientEvents.html", "link": "CharlotteDunois/Livia/LiviaClientEvents.html#method_warn", "name": "CharlotteDunois\\Livia\\LiviaClientEvents::warn", "doc": "&quot;Emitted when something out of expectation occurres. A warning for you.&quot;"},
            
            
            {"type": "Class", "fromName": "CharlotteDunois\\Livia\\Arguments", "fromLink": "CharlotteDunois/Livia/Arguments.html", "link": "CharlotteDunois/Livia/Arguments/Argument.html", "name": "CharlotteDunois\\Livia\\Arguments\\Argument", "doc": "&quot;A fancy argument.&quot;"},
                                                        {"type": "Method", "fromName": "CharlotteDunois\\Livia\\Arguments\\Argument", "fromLink": "CharlotteDunois/Livia/Arguments/Argument.html", "link": "CharlotteDunois/Livia/Arguments/Argument.html#method___construct", "name": "CharlotteDunois\\Livia\\Arguments\\Argument::__construct", "doc": "&quot;Constructs a new Argument. Info is an array as following:&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\Arguments\\Argument", "fromLink": "CharlotteDunois/Livia/Arguments/Argument.html", "link": "CharlotteDunois/Livia/Arguments/Argument.html#method_obtain", "name": "CharlotteDunois\\Livia\\Arguments\\Argument::obtain", "doc": "&quot;Prompts the user and obtains the value for the argument. Resolves with an array of (&#039;value&#039; =&gt; mixed, &#039;cancelled&#039; =&gt; string|null, &#039;prompts&#039; =&gt; Message[], &#039;answers&#039; =&gt; Message[]). Cancelled can be one of user, time and promptLimit.&quot;"},
            
            {"type": "Class", "fromName": "CharlotteDunois\\Livia\\Arguments", "fromLink": "CharlotteDunois/Livia/Arguments.html", "link": "CharlotteDunois/Livia/Arguments/ArgumentCollector.html", "name": "CharlotteDunois\\Livia\\Arguments\\ArgumentCollector", "doc": "&quot;Obtains, validates, and prompts for argument values.&quot;"},
                                                        {"type": "Method", "fromName": "CharlotteDunois\\Livia\\Arguments\\ArgumentCollector", "fromLink": "CharlotteDunois/Livia/Arguments/ArgumentCollector.html", "link": "CharlotteDunois/Livia/Arguments/ArgumentCollector.html#method___construct", "name": "CharlotteDunois\\Livia\\Arguments\\ArgumentCollector::__construct", "doc": "&quot;Constructs a new Argument Collector.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\Arguments\\ArgumentCollector", "fromLink": "CharlotteDunois/Livia/Arguments/ArgumentCollector.html", "link": "CharlotteDunois/Livia/Arguments/ArgumentCollector.html#method_obtain", "name": "CharlotteDunois\\Livia\\Arguments\\ArgumentCollector::obtain", "doc": "&quot;Obtains values for the arguments, prompting if necessary.&quot;"},
            
            {"type": "Class", "fromName": "CharlotteDunois\\Livia", "fromLink": "CharlotteDunois/Livia.html", "link": "CharlotteDunois/Livia/CommandDispatcher.html", "name": "CharlotteDunois\\Livia\\CommandDispatcher", "doc": "&quot;Handles parsing messages and running commands from them.&quot;"},
                                                        {"type": "Method", "fromName": "CharlotteDunois\\Livia\\CommandDispatcher", "fromLink": "CharlotteDunois/Livia/CommandDispatcher.html", "link": "CharlotteDunois/Livia/CommandDispatcher.html#method_addInhibitor", "name": "CharlotteDunois\\Livia\\CommandDispatcher::addInhibitor", "doc": "&quot;Adds an inhibitor. The inhibitor is supposed to return false, if the command should not be blocked. Otherwise it should return a string (as reason) or an array, containing as first element the reason and as second element a Promise (which resolves to a Message), a Message instance or null. The inhibitor can return a Promise (for async computation).&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\CommandDispatcher", "fromLink": "CharlotteDunois/Livia/CommandDispatcher.html", "link": "CharlotteDunois/Livia/CommandDispatcher.html#method_removeInhibitor", "name": "CharlotteDunois\\Livia\\CommandDispatcher::removeInhibitor", "doc": "&quot;Removes an inhibitor.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\CommandDispatcher", "fromLink": "CharlotteDunois/Livia/CommandDispatcher.html", "link": "CharlotteDunois/Livia/CommandDispatcher.html#method_handleMessage", "name": "CharlotteDunois\\Livia\\CommandDispatcher::handleMessage", "doc": "&quot;Handles an incoming message.&quot;"},
            
            {"type": "Class", "fromName": "CharlotteDunois\\Livia", "fromLink": "CharlotteDunois/Livia.html", "link": "CharlotteDunois/Livia/CommandMessage.html", "name": "CharlotteDunois\\Livia\\CommandMessage", "doc": "&quot;A command message.&quot;"},
                                                        {"type": "Method", "fromName": "CharlotteDunois\\Livia\\CommandMessage", "fromLink": "CharlotteDunois/Livia/CommandMessage.html", "link": "CharlotteDunois/Livia/CommandMessage.html#method_parseCommandArgs", "name": "CharlotteDunois\\Livia\\CommandMessage::parseCommandArgs", "doc": "&quot;Parses the argString into usable arguments, based on the argsType and argsCount of the command.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\CommandMessage", "fromLink": "CharlotteDunois/Livia/CommandMessage.html", "link": "CharlotteDunois/Livia/CommandMessage.html#method_run", "name": "CharlotteDunois\\Livia\\CommandMessage::run", "doc": "&quot;Runs the command. Resolves with an instance of Message or an array of Message instances.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\CommandMessage", "fromLink": "CharlotteDunois/Livia/CommandMessage.html", "link": "CharlotteDunois/Livia/CommandMessage.html#method_say", "name": "CharlotteDunois\\Livia\\CommandMessage::say", "doc": "&quot;Responds with a plain message. Resolves with an instance of Message or an array of Message instances.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\CommandMessage", "fromLink": "CharlotteDunois/Livia/CommandMessage.html", "link": "CharlotteDunois/Livia/CommandMessage.html#method_reply", "name": "CharlotteDunois\\Livia\\CommandMessage::reply", "doc": "&quot;Responds with a reply message. Resolves with an instance of Message or an array of Message instances.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\CommandMessage", "fromLink": "CharlotteDunois/Livia/CommandMessage.html", "link": "CharlotteDunois/Livia/CommandMessage.html#method_direct", "name": "CharlotteDunois\\Livia\\CommandMessage::direct", "doc": "&quot;Responds with a direct message. Resolves with an instance of Message or an array of Message instances.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\CommandMessage", "fromLink": "CharlotteDunois/Livia/CommandMessage.html", "link": "CharlotteDunois/Livia/CommandMessage.html#method_parseArgs", "name": "CharlotteDunois\\Livia\\CommandMessage::parseArgs", "doc": "&quot;Parses an argument string into an array of arguments.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\CommandMessage", "fromLink": "CharlotteDunois/Livia/CommandMessage.html", "link": "CharlotteDunois/Livia/CommandMessage.html#method_edit", "name": "CharlotteDunois\\Livia\\CommandMessage::edit", "doc": "&quot;Shortcut to $this-&gt;message-&gt;edit.&quot;"},
            
            {"type": "Class", "fromName": "CharlotteDunois\\Livia", "fromLink": "CharlotteDunois/Livia.html", "link": "CharlotteDunois/Livia/CommandRegistry.html", "name": "CharlotteDunois\\Livia\\CommandRegistry", "doc": "&quot;Handles registration and searching of commands and groups.&quot;"},
                                                        {"type": "Method", "fromName": "CharlotteDunois\\Livia\\CommandRegistry", "fromLink": "CharlotteDunois/Livia/CommandRegistry.html", "link": "CharlotteDunois/Livia/CommandRegistry.html#method_findCommands", "name": "CharlotteDunois\\Livia\\CommandRegistry::findCommands", "doc": "&quot;Finds all commands that match the search string.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\CommandRegistry", "fromLink": "CharlotteDunois/Livia/CommandRegistry.html", "link": "CharlotteDunois/Livia/CommandRegistry.html#method_findGroups", "name": "CharlotteDunois\\Livia\\CommandRegistry::findGroups", "doc": "&quot;Finds all commands that match the search string.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\CommandRegistry", "fromLink": "CharlotteDunois/Livia/CommandRegistry.html", "link": "CharlotteDunois/Livia/CommandRegistry.html#method_resolveCommand", "name": "CharlotteDunois\\Livia\\CommandRegistry::resolveCommand", "doc": "&quot;Resolves a given command, command name or command message to the command.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\CommandRegistry", "fromLink": "CharlotteDunois/Livia/CommandRegistry.html", "link": "CharlotteDunois/Livia/CommandRegistry.html#method_resolveGroup", "name": "CharlotteDunois\\Livia\\CommandRegistry::resolveGroup", "doc": "&quot;Resolves a given commandgroup, command group name or command message to the command group.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\CommandRegistry", "fromLink": "CharlotteDunois/Livia/CommandRegistry.html", "link": "CharlotteDunois/Livia/CommandRegistry.html#method_registerCommand", "name": "CharlotteDunois\\Livia\\CommandRegistry::registerCommand", "doc": "&quot;Registers a command. Emits a commandRegister event for each command.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\CommandRegistry", "fromLink": "CharlotteDunois/Livia/CommandRegistry.html", "link": "CharlotteDunois/Livia/CommandRegistry.html#method_registerCommandsIn", "name": "CharlotteDunois\\Livia\\CommandRegistry::registerCommandsIn", "doc": "&quot;Registers all commands in a directory. The path gets saved as commands path. Emits a commandRegister event for each command.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\CommandRegistry", "fromLink": "CharlotteDunois/Livia/CommandRegistry.html", "link": "CharlotteDunois/Livia/CommandRegistry.html#method_registerGroup", "name": "CharlotteDunois\\Livia\\CommandRegistry::registerGroup", "doc": "&quot;Registers a group. Emits a groupRegister event for each group.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\CommandRegistry", "fromLink": "CharlotteDunois/Livia/CommandRegistry.html", "link": "CharlotteDunois/Livia/CommandRegistry.html#method_registerType", "name": "CharlotteDunois\\Livia\\CommandRegistry::registerType", "doc": "&quot;Registers a type. Emits a typeRegister event for each type.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\CommandRegistry", "fromLink": "CharlotteDunois/Livia/CommandRegistry.html", "link": "CharlotteDunois/Livia/CommandRegistry.html#method_registerTypesIn", "name": "CharlotteDunois\\Livia\\CommandRegistry::registerTypesIn", "doc": "&quot;Registers all types in a directory. Emits a typeRegister event for each type.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\CommandRegistry", "fromLink": "CharlotteDunois/Livia/CommandRegistry.html", "link": "CharlotteDunois/Livia/CommandRegistry.html#method_registerDefaults", "name": "CharlotteDunois\\Livia\\CommandRegistry::registerDefaults", "doc": "&quot;Registers the default argument types, groups, and commands.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\CommandRegistry", "fromLink": "CharlotteDunois/Livia/CommandRegistry.html", "link": "CharlotteDunois/Livia/CommandRegistry.html#method_registerDefaultCommands", "name": "CharlotteDunois\\Livia\\CommandRegistry::registerDefaultCommands", "doc": "&quot;Registers the default commands.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\CommandRegistry", "fromLink": "CharlotteDunois/Livia/CommandRegistry.html", "link": "CharlotteDunois/Livia/CommandRegistry.html#method_registerDefaultGroups", "name": "CharlotteDunois\\Livia\\CommandRegistry::registerDefaultGroups", "doc": "&quot;Registers the default command groups.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\CommandRegistry", "fromLink": "CharlotteDunois/Livia/CommandRegistry.html", "link": "CharlotteDunois/Livia/CommandRegistry.html#method_registerDefaultTypes", "name": "CharlotteDunois\\Livia\\CommandRegistry::registerDefaultTypes", "doc": "&quot;Registers the default argument types.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\CommandRegistry", "fromLink": "CharlotteDunois/Livia/CommandRegistry.html", "link": "CharlotteDunois/Livia/CommandRegistry.html#method_reregisterCommand", "name": "CharlotteDunois\\Livia\\CommandRegistry::reregisterCommand", "doc": "&quot;Reregisters a command. Emits a commandReregister event.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\CommandRegistry", "fromLink": "CharlotteDunois/Livia/CommandRegistry.html", "link": "CharlotteDunois/Livia/CommandRegistry.html#method_unregisterCommand", "name": "CharlotteDunois\\Livia\\CommandRegistry::unregisterCommand", "doc": "&quot;Unregisters a command. Emits a commandUnregister event.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\CommandRegistry", "fromLink": "CharlotteDunois/Livia/CommandRegistry.html", "link": "CharlotteDunois/Livia/CommandRegistry.html#method_resolveCommandPath", "name": "CharlotteDunois\\Livia\\CommandRegistry::resolveCommandPath", "doc": "&quot;Resolves a given group ID and command name to the path.&quot;"},
            
            {"type": "Class", "fromName": "CharlotteDunois\\Livia\\Commands", "fromLink": "CharlotteDunois/Livia/Commands.html", "link": "CharlotteDunois/Livia/Commands/Command.html", "name": "CharlotteDunois\\Livia\\Commands\\Command", "doc": "&quot;A command that can be run in a client.&quot;"},
                                                        {"type": "Method", "fromName": "CharlotteDunois\\Livia\\Commands\\Command", "fromLink": "CharlotteDunois/Livia/Commands/Command.html", "link": "CharlotteDunois/Livia/Commands/Command.html#method___construct", "name": "CharlotteDunois\\Livia\\Commands\\Command::__construct", "doc": "&quot;Constructs a new Command. Info is an array as following:&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\Commands\\Command", "fromLink": "CharlotteDunois/Livia/Commands/Command.html", "link": "CharlotteDunois/Livia/Commands/Command.html#method_hasPermission", "name": "CharlotteDunois\\Livia\\Commands\\Command::hasPermission", "doc": "&quot;Checks if the user has permission to use the command.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\Commands\\Command", "fromLink": "CharlotteDunois/Livia/Commands/Command.html", "link": "CharlotteDunois/Livia/Commands/Command.html#method_run", "name": "CharlotteDunois\\Livia\\Commands\\Command::run", "doc": "&quot;Runs the command. The method must return null, an array of Message instances or an instance of Message, a Promise that resolves to an instance of Message, or an array of Message instances. The array can contain Promises which each resolves to an instance of Message.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\Commands\\Command", "fromLink": "CharlotteDunois/Livia/Commands/Command.html", "link": "CharlotteDunois/Livia/Commands/Command.html#method_reload", "name": "CharlotteDunois\\Livia\\Commands\\Command::reload", "doc": "&quot;Reloads the command.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\Commands\\Command", "fromLink": "CharlotteDunois/Livia/Commands/Command.html", "link": "CharlotteDunois/Livia/Commands/Command.html#method_unload", "name": "CharlotteDunois\\Livia\\Commands\\Command::unload", "doc": "&quot;Unloads the command.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\Commands\\Command", "fromLink": "CharlotteDunois/Livia/Commands/Command.html", "link": "CharlotteDunois/Livia/Commands/Command.html#method_setEnabledIn", "name": "CharlotteDunois\\Livia\\Commands\\Command::setEnabledIn", "doc": "&quot;Enables or disables the command in a guild (or globally).&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\Commands\\Command", "fromLink": "CharlotteDunois/Livia/Commands/Command.html", "link": "CharlotteDunois/Livia/Commands/Command.html#method_isEnabledIn", "name": "CharlotteDunois\\Livia\\Commands\\Command::isEnabledIn", "doc": "&quot;Checks if the command is enabled in a guild (or globally).&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\Commands\\Command", "fromLink": "CharlotteDunois/Livia/Commands/Command.html", "link": "CharlotteDunois/Livia/Commands/Command.html#method_isUsable", "name": "CharlotteDunois\\Livia\\Commands\\Command::isUsable", "doc": "&quot;Checks if the command is usable for a message.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\Commands\\Command", "fromLink": "CharlotteDunois/Livia/Commands/Command.html", "link": "CharlotteDunois/Livia/Commands/Command.html#method_usage", "name": "CharlotteDunois\\Livia\\Commands\\Command::usage", "doc": "&quot;Creates a usage string for the command.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\Commands\\Command", "fromLink": "CharlotteDunois/Livia/Commands/Command.html", "link": "CharlotteDunois/Livia/Commands/Command.html#method_anyUsage", "name": "CharlotteDunois\\Livia\\Commands\\Command::anyUsage", "doc": "&quot;Creates a usage string for any command.&quot;"},
            
            {"type": "Class", "fromName": "CharlotteDunois\\Livia\\Commands", "fromLink": "CharlotteDunois/Livia/Commands.html", "link": "CharlotteDunois/Livia/Commands/CommandGroup.html", "name": "CharlotteDunois\\Livia\\Commands\\CommandGroup", "doc": "&quot;A group for commands.&quot;"},
                                                        {"type": "Method", "fromName": "CharlotteDunois\\Livia\\Commands\\CommandGroup", "fromLink": "CharlotteDunois/Livia/Commands/CommandGroup.html", "link": "CharlotteDunois/Livia/Commands/CommandGroup.html#method___construct", "name": "CharlotteDunois\\Livia\\Commands\\CommandGroup::__construct", "doc": "&quot;Constructs a new Command Group.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\Commands\\CommandGroup", "fromLink": "CharlotteDunois/Livia/Commands/CommandGroup.html", "link": "CharlotteDunois/Livia/Commands/CommandGroup.html#method_setEnabledIn", "name": "CharlotteDunois\\Livia\\Commands\\CommandGroup::setEnabledIn", "doc": "&quot;Enables or disables the group in a guild.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\Commands\\CommandGroup", "fromLink": "CharlotteDunois/Livia/Commands/CommandGroup.html", "link": "CharlotteDunois/Livia/Commands/CommandGroup.html#method_isEnabledIn", "name": "CharlotteDunois\\Livia\\Commands\\CommandGroup::isEnabledIn", "doc": "&quot;Checks if the group is enabled in a guild.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\Commands\\CommandGroup", "fromLink": "CharlotteDunois/Livia/Commands/CommandGroup.html", "link": "CharlotteDunois/Livia/Commands/CommandGroup.html#method_reload", "name": "CharlotteDunois\\Livia\\Commands\\CommandGroup::reload", "doc": "&quot;Reloads all of the group&#039;s commands.&quot;"},
            
            {"type": "Class", "fromName": "CharlotteDunois\\Livia\\Exceptions", "fromLink": "CharlotteDunois/Livia/Exceptions.html", "link": "CharlotteDunois/Livia/Exceptions/CommandFormatException.html", "name": "CharlotteDunois\\Livia\\Exceptions\\CommandFormatException", "doc": "&quot;Has a descriptive message for a command not having proper format.&quot;"},
                    
            {"type": "Class", "fromName": "CharlotteDunois\\Livia\\Exceptions", "fromLink": "CharlotteDunois/Livia/Exceptions.html", "link": "CharlotteDunois/Livia/Exceptions/FriendlyException.html", "name": "CharlotteDunois\\Livia\\Exceptions\\FriendlyException", "doc": "&quot;Has a message that can be considered user-friendly.&quot;"},
                    
            {"type": "Class", "fromName": "CharlotteDunois\\Livia", "fromLink": "CharlotteDunois/Livia.html", "link": "CharlotteDunois/Livia/LiviaClient.html", "name": "CharlotteDunois\\Livia\\LiviaClient", "doc": "&quot;The Command Client, the heart of the framework.&quot;"},
                                                        {"type": "Method", "fromName": "CharlotteDunois\\Livia\\LiviaClient", "fromLink": "CharlotteDunois/Livia/LiviaClient.html", "link": "CharlotteDunois/Livia/LiviaClient.html#method___construct", "name": "CharlotteDunois\\Livia\\LiviaClient::__construct", "doc": "&quot;Constructs a new Command Client. Additional available Client Options are as following:&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\LiviaClient", "fromLink": "CharlotteDunois/Livia/LiviaClient.html", "link": "CharlotteDunois/Livia/LiviaClient.html#method_setCommandPrefix", "name": "CharlotteDunois\\Livia\\LiviaClient::setCommandPrefix", "doc": "&quot;Sets the global command prefix. Null indicates that there is no default prefix, and only mentions will be used. Emits a commandPrefixChange event.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\LiviaClient", "fromLink": "CharlotteDunois/Livia/LiviaClient.html", "link": "CharlotteDunois/Livia/LiviaClient.html#method_isOwner", "name": "CharlotteDunois\\Livia\\LiviaClient::isOwner", "doc": "&quot;Checks whether an user is an owner of the bot.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\LiviaClient", "fromLink": "CharlotteDunois/Livia/LiviaClient.html", "link": "CharlotteDunois/Livia/LiviaClient.html#method_setProvider", "name": "CharlotteDunois\\Livia\\LiviaClient::setProvider", "doc": "&quot;Sets the setting provider to use, and initializes it once the client is ready&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\LiviaClient", "fromLink": "CharlotteDunois/Livia/LiviaClient.html", "link": "CharlotteDunois/Livia/LiviaClient.html#method_getGuildPrefix", "name": "CharlotteDunois\\Livia\\LiviaClient::getGuildPrefix", "doc": "&quot;Get the guild&#039;s prefix - or the default prefix. Null means only mentions.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\LiviaClient", "fromLink": "CharlotteDunois/Livia/LiviaClient.html", "link": "CharlotteDunois/Livia/LiviaClient.html#method_setGuildPrefix", "name": "CharlotteDunois\\Livia\\LiviaClient::setGuildPrefix", "doc": "&quot;Set the guild&#039;s prefix. An empty string means the command prefix will be used. Null means only mentions.&quot;"},
            
            {"type": "Class", "fromName": "CharlotteDunois\\Livia", "fromLink": "CharlotteDunois/Livia.html", "link": "CharlotteDunois/Livia/LiviaClientEvents.html", "name": "CharlotteDunois\\Livia\\LiviaClientEvents", "doc": "&quot;Documents all LiviaClient events (exlucing events from Yasmin).&quot;"},
                                                        {"type": "Method", "fromName": "CharlotteDunois\\Livia\\LiviaClientEvents", "fromLink": "CharlotteDunois/Livia/LiviaClientEvents.html", "link": "CharlotteDunois/Livia/LiviaClientEvents.html#method_commandBlocked", "name": "CharlotteDunois\\Livia\\LiviaClientEvents::commandBlocked", "doc": "&quot;Emitted when a command is prevented from running.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\LiviaClientEvents", "fromLink": "CharlotteDunois/Livia/LiviaClientEvents.html", "link": "CharlotteDunois/Livia/LiviaClientEvents.html#method_commandError", "name": "CharlotteDunois\\Livia\\LiviaClientEvents::commandError", "doc": "&quot;Emitted when a command produces an error while running.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\LiviaClientEvents", "fromLink": "CharlotteDunois/Livia/LiviaClientEvents.html", "link": "CharlotteDunois/Livia/LiviaClientEvents.html#method_commandRun", "name": "CharlotteDunois\\Livia\\LiviaClientEvents::commandRun", "doc": "&quot;Emitted when running a command.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\LiviaClientEvents", "fromLink": "CharlotteDunois/Livia/LiviaClientEvents.html", "link": "CharlotteDunois/Livia/LiviaClientEvents.html#method_unknownCommand", "name": "CharlotteDunois\\Livia\\LiviaClientEvents::unknownCommand", "doc": "&quot;Emitted when an user tries to use an unknown command.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\LiviaClientEvents", "fromLink": "CharlotteDunois/Livia/LiviaClientEvents.html", "link": "CharlotteDunois/Livia/LiviaClientEvents.html#method_commandRegister", "name": "CharlotteDunois\\Livia\\LiviaClientEvents::commandRegister", "doc": "&quot;Emitted when a command is registered.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\LiviaClientEvents", "fromLink": "CharlotteDunois/Livia/LiviaClientEvents.html", "link": "CharlotteDunois/Livia/LiviaClientEvents.html#method_commandReregister", "name": "CharlotteDunois\\Livia\\LiviaClientEvents::commandReregister", "doc": "&quot;Emitted when a command is re-registered.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\LiviaClientEvents", "fromLink": "CharlotteDunois/Livia/LiviaClientEvents.html", "link": "CharlotteDunois/Livia/LiviaClientEvents.html#method_commandUnregister", "name": "CharlotteDunois\\Livia\\LiviaClientEvents::commandUnregister", "doc": "&quot;Emitted when a command is unregistered.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\LiviaClientEvents", "fromLink": "CharlotteDunois/Livia/LiviaClientEvents.html", "link": "CharlotteDunois/Livia/LiviaClientEvents.html#method_groupRegister", "name": "CharlotteDunois\\Livia\\LiviaClientEvents::groupRegister", "doc": "&quot;Emitted when a group is registered.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\LiviaClientEvents", "fromLink": "CharlotteDunois/Livia/LiviaClientEvents.html", "link": "CharlotteDunois/Livia/LiviaClientEvents.html#method_typeRegister", "name": "CharlotteDunois\\Livia\\LiviaClientEvents::typeRegister", "doc": "&quot;Emitted when an argument type is registered.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\LiviaClientEvents", "fromLink": "CharlotteDunois/Livia/LiviaClientEvents.html", "link": "CharlotteDunois/Livia/LiviaClientEvents.html#method_commandPrefixChange", "name": "CharlotteDunois\\Livia\\LiviaClientEvents::commandPrefixChange", "doc": "&quot;Emitted whenever a guild&#039;s command prefix is changed. Guild will be null if the prefix is global. Prefix will be null if it is changed to default.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\LiviaClientEvents", "fromLink": "CharlotteDunois/Livia/LiviaClientEvents.html", "link": "CharlotteDunois/Livia/LiviaClientEvents.html#method_commandStatusChange", "name": "CharlotteDunois\\Livia\\LiviaClientEvents::commandStatusChange", "doc": "&quot;Emitted whenever a command is enabled\/disabled in a guild. Guild will be null if status is global.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\LiviaClientEvents", "fromLink": "CharlotteDunois/Livia/LiviaClientEvents.html", "link": "CharlotteDunois/Livia/LiviaClientEvents.html#method_groupStatusChange", "name": "CharlotteDunois\\Livia\\LiviaClientEvents::groupStatusChange", "doc": "&quot;Emitted whenever a group is enabled\/disabled in a guild. Guild will be null if status is global.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\LiviaClientEvents", "fromLink": "CharlotteDunois/Livia/LiviaClientEvents.html", "link": "CharlotteDunois/Livia/LiviaClientEvents.html#method_warn", "name": "CharlotteDunois\\Livia\\LiviaClientEvents::warn", "doc": "&quot;Emitted when something out of expectation occurres. A warning for you.&quot;"},
            
            {"type": "Class", "fromName": "CharlotteDunois\\Livia\\Providers", "fromLink": "CharlotteDunois/Livia/Providers.html", "link": "CharlotteDunois/Livia/Providers/MySQLProvider.html", "name": "CharlotteDunois\\Livia\\Providers\\MySQLProvider", "doc": "&quot;Loads and stores settings associated with guilds in a MySQL database. Requires the composer package react\/mysql.&quot;"},
                                                        {"type": "Method", "fromName": "CharlotteDunois\\Livia\\Providers\\MySQLProvider", "fromLink": "CharlotteDunois/Livia/Providers/MySQLProvider.html", "link": "CharlotteDunois/Livia/Providers/MySQLProvider.html#method___construct", "name": "CharlotteDunois\\Livia\\Providers\\MySQLProvider::__construct", "doc": "&quot;Constructs a new instance.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\Providers\\MySQLProvider", "fromLink": "CharlotteDunois/Livia/Providers/MySQLProvider.html", "link": "CharlotteDunois/Livia/Providers/MySQLProvider.html#method_clear", "name": "CharlotteDunois\\Livia\\Providers\\MySQLProvider::clear", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\Providers\\MySQLProvider", "fromLink": "CharlotteDunois/Livia/Providers/MySQLProvider.html", "link": "CharlotteDunois/Livia/Providers/MySQLProvider.html#method_create", "name": "CharlotteDunois\\Livia\\Providers\\MySQLProvider::create", "doc": "&quot;Creates a new table row in the db for the guild, if it doesn&#039;t exist already - otherwise loads the row.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\Providers\\MySQLProvider", "fromLink": "CharlotteDunois/Livia/Providers/MySQLProvider.html", "link": "CharlotteDunois/Livia/Providers/MySQLProvider.html#method_destroy", "name": "CharlotteDunois\\Livia\\Providers\\MySQLProvider::destroy", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\Providers\\MySQLProvider", "fromLink": "CharlotteDunois/Livia/Providers/MySQLProvider.html", "link": "CharlotteDunois/Livia/Providers/MySQLProvider.html#method_init", "name": "CharlotteDunois\\Livia\\Providers\\MySQLProvider::init", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\Providers\\MySQLProvider", "fromLink": "CharlotteDunois/Livia/Providers/MySQLProvider.html", "link": "CharlotteDunois/Livia/Providers/MySQLProvider.html#method_get", "name": "CharlotteDunois\\Livia\\Providers\\MySQLProvider::get", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\Providers\\MySQLProvider", "fromLink": "CharlotteDunois/Livia/Providers/MySQLProvider.html", "link": "CharlotteDunois/Livia/Providers/MySQLProvider.html#method_set", "name": "CharlotteDunois\\Livia\\Providers\\MySQLProvider::set", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\Providers\\MySQLProvider", "fromLink": "CharlotteDunois/Livia/Providers/MySQLProvider.html", "link": "CharlotteDunois/Livia/Providers/MySQLProvider.html#method_remove", "name": "CharlotteDunois\\Livia\\Providers\\MySQLProvider::remove", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\Providers\\MySQLProvider", "fromLink": "CharlotteDunois/Livia/Providers/MySQLProvider.html", "link": "CharlotteDunois/Livia/Providers/MySQLProvider.html#method_runQuery", "name": "CharlotteDunois\\Livia\\Providers\\MySQLProvider::runQuery", "doc": "&quot;Runs a SQL query. Resolves with the Command instance.&quot;"},
            
            {"type": "Class", "fromName": "CharlotteDunois\\Livia\\Providers", "fromLink": "CharlotteDunois/Livia/Providers.html", "link": "CharlotteDunois/Livia/Providers/SettingProvider.html", "name": "CharlotteDunois\\Livia\\Providers\\SettingProvider", "doc": "&quot;Loads and stores settings associated with guilds.&quot;"},
                                                        {"type": "Method", "fromName": "CharlotteDunois\\Livia\\Providers\\SettingProvider", "fromLink": "CharlotteDunois/Livia/Providers/SettingProvider.html", "link": "CharlotteDunois/Livia/Providers/SettingProvider.html#method_clear", "name": "CharlotteDunois\\Livia\\Providers\\SettingProvider::clear", "doc": "&quot;Removes all settings in a guild.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\Providers\\SettingProvider", "fromLink": "CharlotteDunois/Livia/Providers/SettingProvider.html", "link": "CharlotteDunois/Livia/Providers/SettingProvider.html#method_destroy", "name": "CharlotteDunois\\Livia\\Providers\\SettingProvider::destroy", "doc": "&quot;Destroys the provider, removing any event listeners.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\Providers\\SettingProvider", "fromLink": "CharlotteDunois/Livia/Providers/SettingProvider.html", "link": "CharlotteDunois/Livia/Providers/SettingProvider.html#method_init", "name": "CharlotteDunois\\Livia\\Providers\\SettingProvider::init", "doc": "&quot;Initializes the provider by connecting to databases and\/or caching all data in memory. LiviaClient::setProvider will automatically call this once the client is ready.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\Providers\\SettingProvider", "fromLink": "CharlotteDunois/Livia/Providers/SettingProvider.html", "link": "CharlotteDunois/Livia/Providers/SettingProvider.html#method_get", "name": "CharlotteDunois\\Livia\\Providers\\SettingProvider::get", "doc": "&quot;Gets a setting from a guild.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\Providers\\SettingProvider", "fromLink": "CharlotteDunois/Livia/Providers/SettingProvider.html", "link": "CharlotteDunois/Livia/Providers/SettingProvider.html#method_set", "name": "CharlotteDunois\\Livia\\Providers\\SettingProvider::set", "doc": "&quot;Sets a setting for a guild.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\Providers\\SettingProvider", "fromLink": "CharlotteDunois/Livia/Providers/SettingProvider.html", "link": "CharlotteDunois/Livia/Providers/SettingProvider.html#method_remove", "name": "CharlotteDunois\\Livia\\Providers\\SettingProvider::remove", "doc": "&quot;Removes a setting from a guild.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\Providers\\SettingProvider", "fromLink": "CharlotteDunois/Livia/Providers/SettingProvider.html", "link": "CharlotteDunois/Livia/Providers/SettingProvider.html#method_getGuildID", "name": "CharlotteDunois\\Livia\\Providers\\SettingProvider::getGuildID", "doc": "&quot;Obtains the ID of the provided guild.&quot;"},
            
            {"type": "Class", "fromName": "CharlotteDunois\\Livia\\Types", "fromLink": "CharlotteDunois/Livia/Types.html", "link": "CharlotteDunois/Livia/Types/ArgumentType.html", "name": "CharlotteDunois\\Livia\\Types\\ArgumentType", "doc": "&quot;An argument type that can be used for argument collecting.&quot;"},
                                                        {"type": "Method", "fromName": "CharlotteDunois\\Livia\\Types\\ArgumentType", "fromLink": "CharlotteDunois/Livia/Types/ArgumentType.html", "link": "CharlotteDunois/Livia/Types/ArgumentType.html#method_validate", "name": "CharlotteDunois\\Livia\\Types\\ArgumentType::validate", "doc": "&quot;Validates a value against the type.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\Types\\ArgumentType", "fromLink": "CharlotteDunois/Livia/Types/ArgumentType.html", "link": "CharlotteDunois/Livia/Types/ArgumentType.html#method_parse", "name": "CharlotteDunois\\Livia\\Types\\ArgumentType::parse", "doc": "&quot;Parses a value into an usable value.&quot;"},
                    {"type": "Method", "fromName": "CharlotteDunois\\Livia\\Types\\ArgumentType", "fromLink": "CharlotteDunois/Livia/Types/ArgumentType.html", "link": "CharlotteDunois/Livia/Types/ArgumentType.html#method_isEmpty", "name": "CharlotteDunois\\Livia\\Types\\ArgumentType::isEmpty", "doc": "&quot;Checks whether a value is considered to be empty. This determines whether the default value for an argument should be used and changes the response to the user under certain circumstances.&quot;"},
            
            {"type": "Class", "fromName": "CharlotteDunois\\Livia\\Utils", "fromLink": "CharlotteDunois/Livia/Utils.html", "link": "CharlotteDunois/Livia/Utils/DataHelpers.html", "name": "CharlotteDunois\\Livia\\Utils\\DataHelpers", "doc": "&quot;Data orientated helpers.&quot;"},
                                                        {"type": "Method", "fromName": "CharlotteDunois\\Livia\\Utils\\DataHelpers", "fromLink": "CharlotteDunois/Livia/Utils/DataHelpers.html", "link": "CharlotteDunois/Livia/Utils/DataHelpers.html#method_disambiguation", "name": "CharlotteDunois\\Livia\\Utils\\DataHelpers::disambiguation", "doc": "&quot;If a selection is ambiguous, this will make a list of selectable items.&quot;"},
            
            {"type": "Class", "fromName": "CharlotteDunois\\Livia\\Utils", "fromLink": "CharlotteDunois/Livia/Utils.html", "link": "CharlotteDunois/Livia/Utils/FileHelpers.html", "name": "CharlotteDunois\\Livia\\Utils\\FileHelpers", "doc": "&quot;File orientated helpers.&quot;"},
                                                        {"type": "Method", "fromName": "CharlotteDunois\\Livia\\Utils\\FileHelpers", "fromLink": "CharlotteDunois/Livia/Utils/FileHelpers.html", "link": "CharlotteDunois/Livia/Utils/FileHelpers.html#method_recursiveFileSearch", "name": "CharlotteDunois\\Livia\\Utils\\FileHelpers::recursiveFileSearch", "doc": "&quot;Performs a recursive file search in the specified path, using the specified search mask.&quot;"},
            
            
                                        // Fix trailing commas in the index
        {}
    ];

    /** Tokenizes strings by namespaces and functions */
    function tokenizer(term) {
        if (!term) {
            return [];
        }

        var tokens = [term];
        var meth = term.indexOf('::');

        // Split tokens into methods if "::" is found.
        if (meth > -1) {
            tokens.push(term.substr(meth + 2));
            term = term.substr(0, meth - 2);
        }

        // Split by namespace or fake namespace.
        if (term.indexOf('\\') > -1) {
            tokens = tokens.concat(term.split('\\'));
        } else if (term.indexOf('_') > 0) {
            tokens = tokens.concat(term.split('_'));
        }

        // Merge in splitting the string by case and return
        tokens = tokens.concat(term.match(/(([A-Z]?[^A-Z]*)|([a-z]?[^a-z]*))/g).slice(0,-1));

        return tokens;
    };

    root.Sami = {
        /**
         * Cleans the provided term. If no term is provided, then one is
         * grabbed from the query string "search" parameter.
         */
        cleanSearchTerm: function(term) {
            // Grab from the query string
            if (typeof term === 'undefined') {
                var name = 'search';
                var regex = new RegExp("[\\?&]" + name + "=([^&#]*)");
                var results = regex.exec(location.search);
                if (results === null) {
                    return null;
                }
                term = decodeURIComponent(results[1].replace(/\+/g, " "));
            }

            return term.replace(/<(?:.|\n)*?>/gm, '');
        },

        /** Searches through the index for a given term */
        search: function(term) {
            // Create a new search index if needed
            if (!bhIndex) {
                bhIndex = new Bloodhound({
                    limit: 500,
                    local: searchIndex,
                    datumTokenizer: function (d) {
                        return tokenizer(d.name);
                    },
                    queryTokenizer: Bloodhound.tokenizers.whitespace
                });
                bhIndex.initialize();
            }

            results = [];
            bhIndex.get(term, function(matches) {
                results = matches;
            });

            if (!rootPath) {
                return results;
            }

            // Fix the element links based on the current page depth.
            return $.map(results, function(ele) {
                if (ele.link.indexOf('..') > -1) {
                    return ele;
                }
                ele.link = rootPath + ele.link;
                if (ele.fromLink) {
                    ele.fromLink = rootPath + ele.fromLink;
                }
                return ele;
            });
        },

        /** Get a search class for a specific type */
        getSearchClass: function(type) {
            return searchTypeClasses[type] || searchTypeClasses['_'];
        },

        /** Add the left-nav tree to the site */
        injectApiTree: function(ele) {
            ele.html(treeHtml);
        }
    };

    $(function() {
        // Modify the HTML to work correctly based on the current depth
        rootPath = $('body').attr('data-root-path');
        treeHtml = treeHtml.replace(/href="/g, 'href="' + rootPath);
        Sami.injectApiTree($('#api-tree'));
    });

    return root.Sami;
})(window);

$(function() {

    // Enable the version switcher
    $('#version-switcher').change(function() {
        window.location = $(this).val()
    });

    
        // Toggle left-nav divs on click
        $('#api-tree .hd span').click(function() {
            $(this).parent().parent().toggleClass('opened');
        });

        // Expand the parent namespaces of the current page.
        var expected = $('body').attr('data-name');

        if (expected) {
            // Open the currently selected node and its parents.
            var container = $('#api-tree');
            var node = $('#api-tree li[data-name="' + expected + '"]');
            // Node might not be found when simulating namespaces
            if (node.length > 0) {
                node.addClass('active').addClass('opened');
                node.parents('li').addClass('opened');
                var scrollPos = node.offset().top - container.offset().top + container.scrollTop();
                // Position the item nearer to the top of the screen.
                scrollPos -= 200;
                container.scrollTop(scrollPos);
            }
        }

    
    
        var form = $('#search-form .typeahead');
        form.typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        }, {
            name: 'search',
            displayKey: 'name',
            source: function (q, cb) {
                cb(Sami.search(q));
            }
        });

        // The selection is direct-linked when the user selects a suggestion.
        form.on('typeahead:selected', function(e, suggestion) {
            window.location = suggestion.link;
        });

        // The form is submitted when the user hits enter.
        form.keypress(function (e) {
            if (e.which == 13) {
                $('#search-form').submit();
                return true;
            }
        });

    
});


