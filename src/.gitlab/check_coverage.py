import xml.etree.ElementTree as ET
import sys
import os
expected_args = [
    "min_coverage",
    "slack_username",
    "slack_channel",
    "slack_icon",
    "slack_url",
    "branch",
    "stage",
    "job",
]
usable_args = {
    "min_coverage":100,
    "slack_channel":'#deploys',
    "slack_username": 'gitlab-ci',
    "slack_icon": 'https://afluenta.com/favicon.png',
    "slack_url": 'https://hooks.slack.com/services/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',
}
for arg in sys.argv:
    for expected_arg in expected_args:
        full_arg = '--' + expected_arg + '='
        current_arg = arg[:len(full_arg)]
        current_value = arg[len(full_arg):]
        if(full_arg == current_arg):
            usable_args[expected_arg] = current_value
        
if os.path.exists('src/phpunit-coverage.xml'):
    tree = ET.parse('src/phpunit-coverage.xml')
    root = tree.getroot()
    coverage = float(root.attrib['line-rate'])
    coverage = round(coverage * 100,2)
    if coverage < float(usable_args['min_coverage']):
        text = "#CIRUN Branch: " + usable_args['branch'] + " | Stage: " + usable_args['stage'] + " | Job: " + usable_args['job'] + " | COVERAGE NOT ENOUGH: " + str(coverage) + "% but min " + str(usable_args['min_coverage']) + "%"
        command = 'curl -X POST --data-urlencode \'payload={"channel":"' + usable_args['slack_channel'] + '","username":"' + usable_args['slack_username'] + '\",\"text\":\"' + text + '\",\"icon_url\":\"' + usable_args['slack_icon'] + '"}\' ' + usable_args['slack_url']
        os.system(command) 
        exit(2)
else:
    coverage=0

print(coverage)