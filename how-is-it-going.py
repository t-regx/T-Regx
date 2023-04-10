#!/usr/bin/python3

import re


SEC_PREFIX = '\t\t#'


def report_stats_in_file(file_path: str) -> None:
    sec_to_cnt = {}
    sec_name = 'none'
    sec_starting = False

    with open(file_path) as input:
        for line in input.readlines():
            if line.startswith(SEC_PREFIX):
                if not sec_starting:
                    sec_name = ''
                    sec_starting = True

                sec_name += line.removeprefix(SEC_PREFIX).strip() + '\n'

            else:
                if sec_starting:
                    sec_starting = False
                    sec_name = sec_name.strip()
                    sec_to_cnt[sec_name] = 0

            match = re.fullmatch(r'\s+count\s*:\s+(?P<count>\d+)\s*', line)
            if match is not None:
                sec_to_cnt[sec_name] += int(match.group('count'))

        for sec_name, count in sec_to_cnt.items():
            print(sec_name)
            print(count)


def main() -> None:
    report_stats_in_file('phpstan.src.neon')
    report_stats_in_file('phpstan.test.neon')


if __name__ == '__main__':
    main()
