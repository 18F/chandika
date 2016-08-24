#!/usr/bin/env python3
#  -*- coding: utf-8 -*-
import argparse
import csv
import re
import json
import http.client

parser = argparse.ArgumentParser(description='Aggregate AWS billing data by account and tag.')
parser.add_argument('--chandika', dest='chandika', help="Chandika hostname")
parser.add_argument('--api-key', dest='api_key', help="Chandika API key")
parser.add_argument('billing_csv', help="Billing CSV file")
parser.add_argument('tag_name', nargs='*', help="Names of tags to aggregate by")
args = parser.parse_args()

costs = {}
totals = {}
statement = 0
month = ''
no_tags = len(args.tag_name) == 0

with open(args.billing_csv) as csvfile:
    reader = csv.DictReader(csvfile)
    for row in reader:
        if row['RecordType'] == 'AccountTotal':
            totals[row['LinkedAccountId']] = row['UnBlendedCost']
        elif row['RecordType'] == 'StatementTotal':
            statement = row['UnBlendedCost']
            match = re.search('\d\d\d\d-\d\d', row['ItemDescription'])
            month = match.group()
        elif row['RecordType'] == 'LineItem':
            if row['LinkedAccountId'] not in costs:
                costs[row['LinkedAccountId']] = { '' : { '' : 0 } } if no_tags else {}
                for tag in args.tag_name:
                    costs[row['LinkedAccountId']][tag] = {}
            for tag in args.tag_name:
                if row['user:' + tag] not in costs[row['LinkedAccountId']][tag]:
                    costs[row['LinkedAccountId']][tag][row['user:' + tag]] = 0
                costs[row['LinkedAccountId']][tag][row['user:' + tag]] = costs[row['LinkedAccountId']][tag][row['user:' + tag]] + float(row['UnBlendedCost'])
            if no_tags:
                costs[row['LinkedAccountId']][''][''] = costs[row['LinkedAccountId']][''][''] + float(row['UnBlendedCost'])

output = { 'provider' : 'Amazon AWS', 'month' : month, 'costs' : costs, 'totals' : totals, 'statement' : statement }

if args.chandika:
    conn = http.client.HTTPConnection(args.chandika, timeout=2)
    conn.request("POST", "/api/billing.php?api_key=" + args.api_key, body=json.dumps(output))
else:
    print(json.dumps(output))
