#!/usr/bin/env python3
#  -*- coding: utf-8 -*-
import argparse
import csv
import pprint
import re

pp = pprint.PrettyPrinter(indent=4)

parser = argparse.ArgumentParser(description='Aggregate AWS billing data by account and tag.')
parser.add_argument('name', help="Provider name")
parser.add_argument('billing_csv', help="Billing CSV file")
parser.add_argument('tag_name', nargs='+', help="Names of tags to aggregate by")
args = parser.parse_args()

costs = {}
totals = {}
statement = 0
month = ''

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
                costs[row['LinkedAccountId']] = {}
                for tag in args.tag_name:
                    costs[row['LinkedAccountId']][tag] = {}
            for tag in args.tag_name:
                if row['user:' + tag] not in costs[row['LinkedAccountId']][tag]:
                    costs[row['LinkedAccountId']][tag][row['user:' + tag]] = 0
                costs[row['LinkedAccountId']][tag][row['user:' + tag]] = costs[row['LinkedAccountId']][tag][row['user:' + tag]] + float(row['UnBlendedCost'])

output = { 'provider' : args.name, 'month' : month, 'costs' : costs, 'totals' : totals, 'statement' : statement }

pp.pprint(output)
