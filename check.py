import os, sys, argparse
from multiprocessing import Pool
from datetime import datetime, timedelta
import logging

def sanity_check(hours, minutes):
    if any(i < 0 for i in [hours, minutes]):
        print 'Cannot process negative numbers.'
        sys.exit(1)

def is_old(time, f):
    ctime = datetime.fromtimestamp(os.stat(f).st_ctime)
    name = os.path.basename(f)
    if name in ['lost+found', __name__, 'index.php', 'index.html']:
        return 'important, won\'t remove {}'.format(name)
    if ctime < time:
        os.remove(f)
        return 'removed {}'.format(name)
    return

def main(target_directory, hours, minutes):
    old = datetime.now() - timedelta(hours=hours, minutes=minutes)
    pool = Pool(processes=2)
    os.chdir(target_directory)
    files = [os.path.realpath(f) for f in os.listdir('.')]
    results = [pool.apply_async(is_old, (old, x, )) for x in files]
    output = filter(lambda x: x is not None, [p.get() for p in results])

if __name__ == '__main__':
    parser = argparse.ArgumentParser(description='Remove files older than a specified period of time.')
    parser.add_argument('target_directory', type=str, help='Target timed directory.')
    parser.add_argument('--hours', '-hr', dest='hours', default=2, type=int)
    parser.add_argument('--minutes', '-m', dest='minutes', default=0, type=int)
    args = parser.parse_args()
    sanity_check(args.hours, args.minutes)
    main(args.target_directory, args.hours, args.minutes)
