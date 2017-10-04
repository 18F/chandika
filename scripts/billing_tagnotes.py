#!/usr/bin/env python3
#  -*- coding: utf-8 -*-
import argparse
import csv
import re
import json
import http.client

parser = argparse.ArgumentParser(description='Update AWS billing data by tag and date.')
parser.add_argument('--chandika', dest='chandika', help="Chandika hostname")
parser.add_argument('--api-key', dest='api_key', help="Chandika API key")
parser.add_argument('--invoice-date', dest='invoice_date', help="Invoice date")
parser.add_argument('billing_notes_csv', help="Billing Notes CSV file")
args = parser.parse_args()

tag_notes = {}
statement = 0
month = ''

with open(args.billing_notes_csv) as csvfile:
    reader = csv.DictReader(csvfile)
    for row in reader:
        tag_notes[row['TagValue']] = row['TagNotes']

invoice_date = args.invoice_date if args.invoice_date else month
output = { 'provider' : 'Amazon AWS', 'invoice_date' : invoice_date, 'tag_notes' : tag_notes }

if args.chandika:
    conn = http.client.HTTPSConnection(args.chandika, timeout=2)
    conn.request("POST", "/api/billing_month.php?api_key=" + args.api_key, body=json.dumps(output))
else:
    print(json.dumps(output))
