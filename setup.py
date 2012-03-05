import os
import sys

from distutils.core import setup

try:
    import argparse
except:
    print ''
    print 'Genecentric requires the "argparse" module which became ',
    print 'available in Python 2.7.'
    print 'You should be able to install it on older versions from PyPI.'
    sys.exit(1)

setup(
    name = 'genecentric',
    author = 'Andrew Gallant',
    author_email = 'Andrew.Gallant@tufts.edu',
    version = '1.0.0',
    license = 'GPL',
    description = 'A utility to generate between-pathway modules (BPMs) and perform GO enrichment on them.',
    long_description = 'See README',
    url = 'http://bcb.cs.tufts.edu/genecentric',
    packages = ['bpm', 'bpm/cmdargs'],
    data_files = [
        ('share/genecentric/doc', ['README', 'LICENSE']),
        ('share/genecentric/data', ['data/essentials', 'data/yeast_emap.gi',
                                    'data/README']),
    ],
    scripts = ['genecentric-bpms', 'genecentric-fainfo',
               'genecentric-from-emap', 'genecentric-go']
)

